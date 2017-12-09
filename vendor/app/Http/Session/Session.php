<?php

namespace App\Vendor\Http\Session;

class Session
{

    /**
     * @var array
     */
    protected $settings;

    public function __construct($settings = []) {
        $defaults = [
            'lifetime'    => '20 minutes',
            'path'        => '/',
            'domain'      => null,
            'secure'      => false,
            'httponly'    => false,
            'name'        => 'app_session_name',
            'autorefresh' => false,
        ];

        $settings = array_merge($defaults, $settings);
        if (is_string($lifetime = $settings['lifetime'])) {
            $settings['lifetime'] = strtotime($lifetime) - time();
        }

        $this->settings = $settings;

        ini_set('session.gc_probability', 1);
        ini_set('session.gc_divisor', 1);
        ini_set('session.gc_maxlifetime', 30 * 24 * 60 * 60);
    }

    public function start() {
        $settings = $this->settings;
        $name = $settings['name'];

        session_set_cookie_params(
            $settings['lifetime'],
            $settings['path'],
            $settings['domain'],
            $settings['secure'],
            $settings['httponly']
        );

        $inactive = session_status() === PHP_SESSION_NONE;

        if ($inactive  && $settings['autorefresh'] && isset($_COOKIE[$name])) {
            setcookie(
                $name,
                $_COOKIE[$name],
                time() + $settings['lifetime'],
                $settings['path'],
                $settings['domain'],
                $settings['secure'],
                $settings['httponly']
            );
        }

        session_name($name);
        session_cache_limiter(false);

        if ($inactive) {
            session_start();
        }
    }

    /**
     * @access public
     * @param string $key
     * @param $value
     * @return Session
     */
    public function set($key, $value){
        $_SESSION[$key] = $value;

        return $this;
    }

    /**
     * @access public
     * @param string $key
     * @param null $default
     * @return mixed
     */
    public function get($key, $default = null) {
        return $this->exists($key) ? $_SESSION[$key] : $default;
    }

    public function delete($key) {
        if ($this->exists($key)) {
            unset($_SESSION[$key]);
        }

        return $this;
    }

    /**
     * @return Session
     */
    public function clear() {
        $_SESSION = [];

        return $this;
    }

    /**
     * @param $key
     * @return bool
     */
    public function exists($key) {
        return array_key_exists($key, $_SESSION);
    }

    /**
     * @param bool $new
     * @return string
     */
    public static function id($new = false) {
        if ($new && session_id()) {
            session_regenerate_id(true);
        }

        return session_id() ?: '';
    }

    public static function destroy() {
        if (self::id()) {
            session_unset();
            session_destroy();
            session_write_close();
            if (ini_get('session.use_cookies')) {
                $params = session_get_cookie_params();
                setcookie(
                    session_name(),
                    '',
                    time() - 4200,
                    $params['path'],
                    $params['domain'],
                    $params['secure'],
                    $params['httponly']
                );
            }
        }
    }

}
