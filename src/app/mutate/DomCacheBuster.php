<?php

class Mutate_DomCacheBuster
{
    public function __invoke(Tell_Dom $dom)
        : Tell_Dom
    {
        $self = $this;

        $dom('head link[href]')->each(function ($i, $n) use ($self) {
            $n->attr('href', $n->attr('href') . '?v=' . $self->getHash());
        });

        $dom('head script[src]')->each(function ($i, $n) use ($self) {
            $n->attr('src', $n->attr('src') . '?v=' . $self->getHash());
        });

        return $dom;
    }

    public function getHash()
        : string
    {
        $css = ROOT_PATH . 'public/css/styles.css';
        $js  = ROOT_PATH . 'public/js/user.min.js';
        $val = Tell::config('cache_buster');

        if (is_file($css)) {
            $val .= filemtime($css);
        }

        if (is_file($js)) {
            $val .= filemtime($js);
        }

        return md5($val);
    }
}
