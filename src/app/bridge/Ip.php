<?php

/**
 * IP Address
 * ---
 * @author  Tell Konkle <tellkonkle@gmail.com>
 */
class Ip
{
    /**
     * IPv4 CIDR notation masks used for determining if an IP is a trusted IP proxy handler.
     * ---
     * @var  array
     */
    protected static $proxies = [
        '173.245.48.0/20',  // CloudFlare
        '103.21.244.0/22',  // CloudFlare
        '103.22.200.0/22',  // CloudFlare
        '103.31.4.0/22',    // CloudFlare
        '141.101.64.0/18',  // CloudFlare
        '108.162.192.0/18', // CloudFlare
        '190.93.240.0/20',  // CloudFlare
        '188.114.96.0/20',  // CloudFlare
        '197.234.240.0/22', // CloudFlare
        '198.41.128.0/17',  // CloudFlare
        '162.158.0.0/15',   // CloudFlare
        '104.16.0.0/12',    // CloudFlare
        '172.64.0.0/13',    // CloudFlare
        '131.0.72.0/22',    // CloudFlare
    ];

    /**
     * Get the trusted IP address used to make the current request.
     * ---
     * @return  string  Client's trusted IP address.
     */
    public static function trusted()
        : string
    {
        return self::ipv4Range(self::current(), self::$proxies)
            ? self::proxy()
            : self::current();
    }

    /**
     * Get the IP address used to make the current request.
     * ---
     * @return  string  Client's IP address (falls back to '127.0.0.1' for CLI).
     */
    public static function current()
        : string
    {
        return $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    }

    /**
     * Get the proxy IP address used to make the current request.
     * ---
     * @return  string  End-user's IP address.
     */
    public static function proxy()
    {
        // CloudFlare; shared IP; proxy IP
        $cloud   = $_SERVER['HTTP_CF_CONNECTING_IP'] ?? NULL;
        $client  = $_SERVER['HTTP_CLIENT_IP'] ?? NULL;
        $forward = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? NULL;

        // (string) Cloudflare proxy IP
        if ($cloud && filter_var($cloud, FILTER_VALIDATE_IP)) {
            return $cloud;
        }

        // (string) Shared IP
        if ($client && filter_var($client, FILTER_VALIDATE_IP)) {
            return $client;
        }

        // (string) Generic proxy
        if ($forward && filter_var($forward, FILTER_VALIDATE_IP)) {
            return $forward;
        }

        // (string) Real IP address
        return self::current();
    }

    /**
     * Verify that an IPv4 address falls within a specified IPv4 CIDR notation mask.
     * ---
     * @param   mixed         Value to validate.
     * @param   string|array  CIDR notation mask(s) to validate against.
     * @return  bool          TRUE if input IP is within specified IP range, FALSE otherwise.
     * @link    https://stackoverflow.com/a/594134/1148902
     */
    public static function ipv4Range($input, $cidr)
    {
        // (bool) IP isn't valid (or is IPv6); cannot determine if within CIDR; stop here
        if ( ! filter_var($input, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            return FALSE;
        }

        // Loop through and evaluate IP address against each CIDR notation
        foreach ((array) $cidr as $range) {
            // 10.2.0.0/16 --> (10.2.0.0, 16)
            list($subnet, $mask) = explode('/', $range, 2);

            // (int) Normalize
            $mask = $mask ?? 32;

            // (int)
            $ip = ip2long($input);

            // (int)
            $subnet = ip2long($subnet);

            // (bitmask)
            $mask = -1 << (32 - $mask);

            // (bitmask) Normalize
            $subnet = $subnet & $mask;

            // (bool) IP is within a specified IP range
            if (($ip & $mask) == $subnet) {
                return TRUE;
            }
        }

        // (bool) IP address doesn't exist in any of the specified IP ranges
        return FALSE;
    }
}
