<?php

if (!function_exists('kelipatan_validasi')) {
    // Validasi apakah qty sudah sesuai kelipatan
    function kelipatan_validasi(int $qty, int $step): bool
    {
        if ($step <= 1) return true;
        return $qty % $step === 0;
    }
}

if (!function_exists('kelipatan_generate')) {
    // Generate daftar kelipatan dari step hingga max
    function kelipatan_generate(int $step, int $max = 100): array
    {
        if ($step <= 1) {
            return range(1, max(1, $max));
        }

        $result = [];
        for ($i = $step; $i <= $max; $i += $step) {
            $result[] = $i;
        }

        return $result;
    }
}

if (!function_exists('kelipatan_floor')) {
    // Kelipatan terdekat ke bawah
    function kelipatan_floor(int $qty, int $step): int
    {
        if ($step <= 1) return $qty;
        return (int)(floor($qty / $step) * $step);
    }
}

if (!function_exists('kelipatan_ceil')) {
    // Kelipatan terdekat ke atas
    function kelipatan_ceil(int $qty, int $step): int
    {
        if ($step <= 1) return $qty;
        return (int)(ceil($qty / $step) * $step);
    }
}

if (!function_exists('kelipatan_suggest')) {
    // Saran kelipatam terdekat
    function kelipatan_suggest(int $qty, int $step): array
    {
        return [
            'floor' => kelipatan_floor($qty, $step),
            'ceil'  => kelipatan_ceil($qty, $step),
        ];
    }
}

if (!function_exists('kelipatan_analisis')) {
    // Analisis lengkap kelipatan
    function kelipatan_analisis(int $qty, int $step): array
    {
        return [
            'qty'     => $qty,
            'step'    => $step,
            'valid'   => kelipatan_validasi($qty, $step),
            'floor'   => kelipatan_floor($qty, $step),
            'ceil'    => kelipatan_ceil($qty, $step),
            'nearest' => (abs($qty - kelipatan_floor($qty, $step)) 
                        <= abs(kelipatan_ceil($qty, $step) - $qty))
                        ? kelipatan_floor($qty, $step)
                        : kelipatan_ceil($qty, $step),
        ];
    }
}

if (!function_exists('kelipatan_pesan')) {
    // Pesan error untuk kelipatan
    function kelipatan_pesan(int $step): string
    {
        return $step <= 1 
            ? 'Bebas qty'
            : "Qty harus kelipatan {$step}";
    }
}

if (!function_exists('kelipatan_next')) {
    function kelipatan_next(int $qty, int $step): int
    {
        if ($step <= 1) return $qty + 1;
        return kelipatan_ceil($qty + 1, $step);
    }
}

if (!function_exists('kelipatan_prev')) {
    function kelipatan_prev(int $qty, int $step): int
    {
        if ($step <= 1) return max(1, $qty - 1);
        return max($step, kelipatan_floor($qty - 1, $step));
    }
}