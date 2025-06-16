<!DOCTYPE html>
<html>
<head>
  <title>Xeedwallet-Email</title>
</head>
<body style="background-color: #ffffff; margin: 0; padding: 0;">
  <table width="100%" border="0" cellspacing="0" cellpadding="0" style="min-width: 100%;">
    <tr>
      <td align="center" valign="top" style="background-color: #f2d945;">
        <table width="600" border="0" cellspacing="0" cellpadding="0" style="min-width: 600px;">
          <tr>
            <td align="center" valign="top" style="background-color: #ffffff; padding: 20px;">
              <img src="{{asset('uploads/logos/1712202691_logo.png')}}" alt="Logo" width="174" height="44" style="display: block; margin: 0 auto;">
              <h1 style="font-size: 30px; font-weight: 700; color: #000000; text-align: center">Email confirmation</h1>
              <p style="font-size: 15px; font-weight: 400; color: #a3a2a2;">Dear {{$full_name}},</p>
              <p style="font-size: 15px; font-weight: 400; color: #a3a2a2;">Thanks very much for your kind registration in xeedwallet.</p>
              <p style="font-size: 15px; font-weight: 400; color: #a3a2a2;">You are one step away from finishing registration in xeedwallet.</p>
              <p style="font-size: 15px; font-weight: 400; color: #a3a2a2;">The otp code: <strong>{{$otp_code}}</strong></p>
              <p style="font-size: 15px; font-weight: 400; color: #a3a2a2;">&nbsp;</p>
              <p style="font-size: 15px; font-weight: 400; color: #a3a2a2;">Thanks very much</p>
              <p style="font-size: 15px; font-weight: 400; color: #a3a2a2;">xeedwallet</p>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>