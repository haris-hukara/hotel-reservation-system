<?php

class Config {

    const DATE_FORMAT = "Y-m-d H:i:s";

    public static function DB_HOST(){
      return Config::get_env("DB_HOST", "sql11.freesqldatabase.com");
    }
    public static function DB_USERNAME(){
      return Config::get_env("DB_USERNAME", "sql11512689");
    }
    public static function DB_PASSWORD(){
      return Config::get_env("DB_PASSWORD", "18vqt2GEyy");
    }
    public static function DB_SCHEME(){
      return Config::get_env("DB_SCHEME", "sql11512689");
    }
    public static function DB_PORT(){
      return Config::get_env("DB_PORT", "3306");
    }


    public static function JWT_SECRET(){
      return Config::get_env("JWT_SECRET", "ezcb9s8UcF");
    }
      // 86400 = 1 day in sec
    public static function JWT_TOKEN_TIME(){
      return Config::get_env("JWT_TOKEN_TIME", 86400);
    }
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
