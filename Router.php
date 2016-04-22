<?php
/**
 *
 *  A Simple Static Route for PHP
 *
 *  @author Carbos https://github.com/carbos10
 *
 *  @version 1.0.0
 *
 */
class Route {
  /**
   * @var string  contain the REQUEST_METHOD
   */
  private static $method;

  /**
   * @var string  contain the REQUEST_URI
   */
  private static $uri;

  /**
   * @var string  contain the temporary value to add when you use the static method group
   */
  private static $group;

  /**
   * @var boolval   it is true if $uri is equal to url of route
   */
  private static $flag = false;

  /**
   *  This is for access to get, post, delete, put methods
   *
   *  @param string $method contain the name of method to check(name of method)
   *  @param mixed[]  contain parameters of method
   *
   *  @return void
   */
  public static function __callStatic($method, $args)
  {
    self::getRequestMethod();
    self::getRequestUri();
    if(self::$method == strtoupper($method)){
      self::run($args[1], $args[0]);
    }
  }

  /**
   *  Get the Request Method, change value of $method
   *
   *  @return void
   */
  private static function getRequestMethod()
  {
    if(empty(self::$method)) self::$method = $_SERVER['REQUEST_METHOD'];
  }

  /**
   *  Get the Request Uri, change value of $uri
   *
   *  @return void
   */
  private static function getRequestUri()
  {
    if(empty(self::$uri)) self::$uri = "/".trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), "/");
  }

  /**
   * This function call the callback when the url of route is equal to $uri
   *
   *  @param  callback  $callback It contains the callback to user function
   *  @param string   $url  Contains the url of the Route
   *
   *  @return void;
   */
  private static function run($callback, $url)
  {
    $pattern = "#^".self::$group.$url."$#";

    if(preg_match_all($pattern , self::$uri, $array, PREG_SET_ORDER) == true)
    {
      self::$flag = true;
      array_splice($array[0], 0, 1);
      call_user_func_array($callback, $array[0]);
    }
  }

  /**
   *  Use a lot of routes for only one dir
   *
   *  @param string $string   Contains the dir to add to url route
   *  @param callback $callback   Contains the function with all route to do with this
   *
   *  @return void
   */
  public static function group($string, $callback)
  {
    self::$group = $string;
    call_user_func($callback);
    self::$group = null;
  }

  /**
   *  Check if there are route actived
   *
   *  @param callback $callback Function to do
   *
   *  @return void
   */
  public static function error404($callback)
  {
    if(!self::$flag) call_user_func($callback);
  }

}
