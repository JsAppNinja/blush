<?php
$msg = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</head>
<body style="background-color: #f7f8fa;">
<table cellpadding="0" border="0"
       style="-webkit-text-size-adjust: none; -ms-text-size-adjust: none; background-color: #f7f8fa; line-height: 1.2em; font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; border-spacing: 0px; font-size: 100%; border: 0; margin: 0; padding: 0; height: 100%;"
       cellspacing="0" width="100%" class="container">

    <tr>
      <td width="100%" align="center">
        <table cellpadding="0" border="0"
          style="border-spacing: 0; font-size: 100%; border: 0; margin: 0; padding: 0; border-spacing: 0; width: 580px; min-width: 370px;margin-top:20px" cellspacing="0">
          <tr>
            <td align="left">
              <a href="' . $CI->config->item('signin_url') . '"><img src="' . site_url('../assets/images/logo.png') . '" width="115" height="55"/></a>
            </td>
          </tr>
        </table>
      </td>
    </tr>
    <tr>
        <td width="100%" align="center">

            <table cellpadding="0" border="0"
                   style="line-height: 1.2em; font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; border-spacing: 0; font-size: 100%; border: 0; margin: 0; padding: 0; border-spacing: 0; background-color: #fff; border: 1px solid #e8e9eb; width: 580px; min-width: 370px;border-radius: 5px; margin-top:10px;"
                   cellspacing="0">
                <tbody>
                <tr>
                    <td>

                        <table cellpadding="0" border="0"
                               style="border-bottom: 1px solid #ebebeb; background-color: #fff; line-height: 1.2em; font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; border-spacing: 0; font-size: 100%; width: 580px;  min-width: 580px;padding: 15px 0 30px;border-radius: 5px;"
                               cellspacing="0">
                            <tbody>
                            <tr valign="top" align="center">
                                <td>
                                    <table cellpadding="0" border="0"
                                           style="background-color: #fff; line-height: 1.2em; font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; border-spacing: 0; font-size: 100%; max-width: 580px; min-width: 580px;"
                                           cellspacing="0">
                                        <tbody>
                                        <tr valign="top">
                                            <td style="max-width: 420px; min-width: 440px; padding: 0; color: #585a5e; line-height: 24px; font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; border-spacing: 0; text-align: left;">
                                                <div style="line-height: 1.5; font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; font-size: 14px; color: #81898F; text-shadow: none; margin: 0; padding:0 30px 0; ">
                                                    <p style="font-weight: 500;color: #576269;font-size:18px;margin-bottom:10px">Hello ' . $user->firstname . " " . $user->lastname . ',</p>';

?>