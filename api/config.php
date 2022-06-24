<?php

class Config {

    const DATE_FORMAT = "Y-m-d H:i:s";

    public static function DB_HOST(){
      return Config::get_env("DB_HOST", "localhost");
    }
    public static function DB_USERNAME(){
      return Config::get_env("DB_USERNAME", "root");
    }
    public static function DB_PASSWORD(){
      return Config::get_env("DB_PASSWORD", "root");
    }
    public static function DB_SCHEME(){
      return Config::get_env("DB_SCHEME", "hotelsea");
    }
    public static function DB_PORT(){
      return Config::get_env("DB_PORT", "3306");
    }

    const JWT_SECRET = "JWT SECRET";
    const JWT_TOKEN_TIME = 604800; // exp time for JWT token in seconds

      public static function SMTP_HOST(){
        return Config::get_env("SMTP_HOST", "smtp.gmail.com");
      }
      public static function SMTP_PORT(){
        return Config::get_env("SMTP_PORT", "587");
      }
      public static function SMTP_USER(){
        return Config::get_env("SMTP_USER", "webproject.webshop@gmail.com");
      }
      public static function SMTP_PASSWORD(){
        return Config::get_env("SMTP_PASSWORD", "tgxajeuifyrbxyjo");
      }
      public static function get_env($name, $default){
        return isset($_ENV[$name]) && trim($_ENV[$name]) != '' ? $_ENV[$name] : $default;
      }

}   

?>
