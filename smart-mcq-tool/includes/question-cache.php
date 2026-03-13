<?php

class SMPP_Question_Cache {
    public function get($key) {
        return get_transient('smpp_' . $key);
    }

    public function set($key, $value, $ttl = 300) {
        set_transient('smpp_' . $key, $value, $ttl);
    }
}
