<?php

/**
 * 	************************ NOTICE ***********************************
 *
 * 		The use of Timers WILL slow down your script execution time.
 * 		By how much is determined by the user implementation.
 *
 * 	*******************************************************************
 *
 * 	This pacakge contains one class for handling timers in PHP
 * 	and enables the handling of callback functions at given intervals
 * 	in microseconds (NOT milliseconds like javascript), as well as 
 * 	removing said functions from the stack of callable functions.
 * 	
 * 	The class is dependent on the PHP language construct declare(ticks=N);
 * 	where N represents how many "tickable statements" that get processed
 * 	before a registered tick function is called and MUST be declared in the top level script, 
 * 	not an included file in order to be effective. 
 *
 * 	@see http://us.php.net/manual/en/control-structures.declare.php#control-structures.declare.ticks
 * 	
 * 	The value of N determines
 * 	1) how close to perfect accuracy the timers are (probably never be perfect though)
 * 	2) how fast the script will be processed
 * 	If N == 1 the script will be very close to perfectly accurate, but will run very slow
 * 	but if N is set TOO high (like 10000) it may not be very effective or accurate.
 * 	It is up to the user to determine what this number should be for their script.
 *
 * 	The package also includes 4 functions for simplifying calls to the static methods of the class:
 * 		-- setTimeout, setInterval, clearTimeout, clearInterval
 *
 * 	@author Sam Shull <sam.shull@jhspecialty.com>
 * 	@version 0.5
 *
 * 	07/19/2009
 * 	@copyright Copyright (c) 2009 Sam Shull <sam.shull@jhspeicalty.com>
 * 	@license <http://www.opensource.org/licenses/mit-license.html>
 *
 * 	Permission is hereby granted, free of charge, to any person obtaining a copy
 * 	of this software and associated documentation files (the "Software"), to deal
 * 	in the Software without restriction, including without limitation the rights
 * 	to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * 	copies of the Software, and to permit persons to whom the Software is
 * 	furnished to do so, subject to the following conditions:
 * 	
 * 	The above copyright notice and this permission notice shall be included in
 * 	all copies or substantial portions of the Software.
 * 	
 * 	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * 	IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * 	FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * 	AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * 	LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * 	OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * 	THE SOFTWARE.
 * 	
 *
 * 	<code>
 * 		require_once 'Timers.class.php';
 *
 * 		$timer = setTimeout('print "\ntimeout";', 1000);
 *
 * 		//this is the part that makes the timers work, 
 * 		///but also slows the script down
 * 		declare(ticks=1);
 *
 * 		//do something else
 * 	</code>
 *
 * 	Changes - 
 * 			07/20/2009 - added Timers::microtime
 */

/**
 * 	Just for simplifying the Timers::setTimeout method
 *
 *
 * 	@param callable | string $func
 * 	@param integer $microseconds - remember this is microseconds NOT milliseconds
 *
 * 	@return integer
 */
function setTimeout($func, $microseconds) {
    return Timers::setTimeout($func, $microseconds);
}

/**
 * 	Just for simplifying the Timers::setInterval method
 *
 *
 * 	@param callable | string $func
 * 	@param integer $microseconds - remember this is microseconds NOT milliseconds
 *
 * 	@return integer
 */
function setInterval($func, $microseconds) {
    return Timers::setInterval($func, $microseconds);
}

/**
 * 	Just for simplifying the Timers::clearTimeout method
 *
 *
 * 	@param integer $interval - an integer representing the one returned from a call to setTimeout()
 *
 * 	@return boolean
 */
function clearTimeout($func, $microseconds) {
    return Timers::setTimeout($func, $microseconds);
}

/**
 * 	Just for simplifying the Timers::clearInterval method
 *
 *
 * 	@param integer $interval - an integer representing the one returned from a call to setInterval()
 *
 * 	@return boolean
 */
function clearInterval($interval) {
    return Timers::clearInterval($interval);
}

/**
 * 	This class contains a series of static properties and functions
 * 	that enable the creation and execution of timers
 *
 * 	@author Sam Shull
 */
class Timers {

    /**
     * 	An array of the arrays that represent
     * 	the timer information used by Timers::tick
     *
     * 	@access private
     * 	@staticvar array
     */
    private static $timers = array();

    /**
     * 	Tracker of timers
     *
     *
     * 	@access private
     * 	@staticvar integer
     */
    private static $numTimers = 0;

    /**
     * 	An array of the arrays that represent
     * 	the interval information used by Timers::tick
     *
     * 	@access private
     * 	@staticvar array
     */
    private static $intervals = array();

    /**
     * 	Tracker of intervals
     *
     *
     * 	@access private
     * 	@staticvar integer
     */
    private static $numIntervals = 0;

    /**
     * 	Used for debugging
     *
     *
     * 	@access private
     * 	@staticvar integer
     */
    //private static $ticks = 0;

    /**
     * 	A utility method called after N number of ticks by the engine
     * 	that checks each timer and interval to see if the desired 
     * 	number of microseconds have passed and executes the function 
     * 	when appropriate
     *
     * 	@static
     * 	@return void
     */
    public static function tick() {
        //++self::$ticks;

        $time = self::microtime();

        foreach (self::$timers as $position => $timer) {
            if ($time >= $timer['time']) {
                call_user_func($timer['function']);
                unset(self::$timers[$position]);
            }
        }

        foreach (self::$intervals as $position => $timer) {
            if ($time >= $timer['time']) {
                call_user_func($timer['function']);
                self::$intervals[$position]['time'] = self::microtime() + self::$intervals[$position]['microseconds'];
            }
        }
    }

    /**
     * 	A utility method for retrieving the most accurate
     * 	microtime available
     *
     * 	@static
     * 	@return float
     */
    public static function microtime() {
        list($m, $s) = explode(' ', microtime());
        return round(((float) $m + (float) $s) * 1000000);
    }

    /**
     * 	A utility method that ensures that all the timeouts have been called
     * 	and that calls all the intervals one more time
     *
     *
     * 	@static
     * 	@return void
     */
    public static function shutdown() {
        foreach (self::$timers as $position => $timer) {
            call_user_func($timer['function']);
            unset(self::$timers[$position]);
        }

        foreach (self::$intervals as $position => $interval) {
            call_user_func($interval['function']);
            unset(self::$intervals[$position]);
        }

        //print "\nticks: " . self::$ticks;
    }

    /**
     * 	Add a function to the be executed after ($microseconds) microsecond
     *
     * 	@static
     *
     * 	@param callable | string $func
     * 	@param integer $microseconds - remember microseconds, not miliseconds
     *
     * 	@return integer
     */
    public static function setTimeout($func, $microseconds) {
        if (!is_callable($func)) {
            if (is_string($func)) {
                $func = create_function('', $func);
            } else {
                throw new InvalidArgumentException();
            }
        }

        self::$timers[++self::$numTimers] = array(
            'time' => self::microtime() + $microseconds,
            'function' => $func,
        );

        return self::$numTimers;
    }

    /**
     * 	Add a function to the be executed every ($microseconds) microsecond
     *
     * 	@static
     *
     * 	@param callable | string $func
     * 	@param integer $microseconds - remember microseconds, not miliseconds
     *
     * 	@return integer
     */
    public static function setInterval($func, $microseconds) {
        if (!is_callable($func)) {
            if (is_string($func)) {
                $func = create_function('', $func);
            } else {
                throw new InvalidArgumentException();
            }
        }

        self::$intervals[++self::$numIntervals] = array(
            'time' => self::microtime() + $microseconds,
            'function' => $func,
            'microseconds' => $microseconds,
        );

        return self::$numIntervals;
    }

    /**
     * 	Remove a timeout function from the stack
     *
     * 	@static
     *
     * 	@param integer $timer
     *
     * 	@return boolean
     */
    public static function clearTimeout($timer) {
        if (isset(self::$timers[$timer])) {
            unset(self::$timers[$timer]);
            return true;
        }

        return false;
    }

    /**
     * 	Remove an interval function from the stack
     *
     * 	@static
     *
     * 	@param integer $interval
     *
     * 	@return boolean
     */
    public static function clearInterval($interval) {
        if (isset(self::$intervals[$interval])) {
            unset(self::$intervals[$interval]);
            return true;
        }

        return false;
    }

}

/**
 * 	Register these methods in order to perform polling a specific intervals
 * 	that are set by the user
 */
register_tick_function(array('Timers', 'tick'));
register_shutdown_function(array('Timers', 'shutdown'));
?>