<?php if (!function_exists("a_s_c_v")) {
    function a_s_c_v()
    {
        return a_cpm_c_v();
    }
}
if (!function_exists("c_pm_c_v")) {
    function c_pm_c_v()
    {
        return a_lg_c_v();
    }
}
if (!function_exists("u_rp_c_v")) {
    function u_rp_c_v()
    {
        return u_w_c_v();
    }
}
if (!function_exists("a_tc_c_v")) {
    function a_tc_c_v()
    {
        return a_wt_c_v();
    }
}
if (!function_exists("a_u_c_v")) {
    function a_u_c_v()
    {
        return u_rp_c_v();
    }
}
if (!function_exists("a_lg_c_v")) {
    function a_lg_c_v()
    {
        //return n_as_k_c();
        return 0;
    }
}
if (!function_exists("u_w_c_v")) {
    function u_w_c_v()
    {
        return c_pm_c_v();
    }
}
if (!function_exists("a_mt_c_v")) {
    function a_mt_c_v()
    {
        return a_ut_c_v();
    }
}
if (!function_exists("a_dt_c_v")) {
    function a_dt_c_v()
    {
        return a_t_c_v();
    }
}
if (!function_exists("g_e_v")) {
    function g_e_v()
    {
        return env(a_k());
    }
}
if (!function_exists("a_k")) {
    function a_k()
    {
        return base64_decode("SU5TVEFMTF9BUFBfU0VDUkVU");
    }
}
if (!function_exists("g_d")) {
    function g_d()
    {
        return str_replace(
            ["https://www.", "http://www.", "https://", "http://", "www."],
            "",
            request()->getHttpHost()
        );
    }
}
if (!function_exists("u_sm_c_v")) {
    function u_sm_c_v()
    {
        return a_adn_c_v();
    }
}
if (!function_exists("g_c_v")) {
    function g_c_v()
    {
        return cache("a_s_k");
    }
}
if (!function_exists("p_c_v")) {
    function p_c_v()
    {
        return cache(["a_s_k" => g_e_v()], 2629746);
    }
}
if (!function_exists("a_te_c_v")) {
    function a_te_c_v()
    {
        return a_tc_c_v();
    }
}
if (!function_exists("a_ut_c_v")) {
    function a_ut_c_v()
    {
        return a_u_c_v();
    }
}
if (!function_exists("a_t_c_v")) {
    function a_t_c_v()
    {
        return a_mt_c_v();
    }
}
if (!function_exists("a_adn_c_v")) {
    function a_adn_c_v()
    {
        return a_s_c_v();
    }
}
if (!function_exists("a_cpm_c_v")) {
    function a_cpm_c_v()
    {
        return a_te_c_v();
    }
}
if (!function_exists("a_wt_c_v")) {
    function a_wt_c_v()
    {
        return a_dt_c_v();
    }
}
