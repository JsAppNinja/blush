<?php
$msg .= '
</div>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>

</td>
</tr>
</table>


<table cellpadding="0" border="0"
       style="line-height: 1.2em; font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; border-spacing: 0; font-size: 100%;  margin-top:20px; margin-bottom:40px;"
       cellspacing="0" width="620" align="center">
    <tbody>

    <tr valign="top">
        <td width="620" style="border-spacing: 0;text-align: center;">
            <p style="text-align: center; margin: 0; padding: 5px 4px; font-size: 12px; color: #b8b8ba;
                                  font-weight: regular; line-height: 16px; font-family: \'Helvetica Neue\', Helvetica,
                                  Arial, sans-serif;  text-shadow: 0 1px 0 #fff;">

                You received this message because you have an account with ' . $CI->config->item('site_title') . '.
                <a style="color: #ff6494" href="' . $CI->config->item('signin_url') . '">Click here to log in.</a>
                </a>
            </p>
        </td>
    </tr>

    </tbody>
</table>
</td>
</tr>
</table>
</body>
</html>';
?>
