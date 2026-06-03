<!doctype html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="x-ua-compatible" content="IE=edge">
  <meta name="color-scheme" content="light only">
  <meta name="supported-color-schemes" content="light only">
  <title>@yield('subject', 'Goal Shot Ball Association of Bihar')</title>
  <!--[if mso]>
  <style>* { font-family: Arial, Helvetica, sans-serif !important; }</style>
  <![endif]-->
  <style>
    /* Web-safe fallbacks; brand fonts load where supported */
    body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
    table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
    img { -ms-interpolation-mode: bicubic; border: 0; outline: none; text-decoration: none; }
    a { color: #2563EB; }
    @media only screen and (max-width: 620px) {
      .gsb-container { width: 100% !important; }
      .gsb-px { padding-left: 24px !important; padding-right: 24px !important; }
      .gsb-stack { display: block !important; width: 100% !important; }
      .gsb-h1 { font-size: 28px !important; line-height: 34px !important; }
    }
  </style>
</head>

<body style="margin:0; padding:0; background-color:#FFFBEB;">
  <!-- Preheader (hidden) -->
  <div style="display:none; max-height:0; overflow:hidden; mso-hide:all; font-size:1px; line-height:1px; color:#FFFBEB;">
    @yield('preheader', 'Goal Shot Ball Association of Bihar')
  </div>

  <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0"
    style="background-color:#FFFBEB;">
    <tr>
      <td align="center" style="padding:32px 16px;">

        <!-- Card -->
        <table role="presentation" class="gsb-container" width="600" cellpadding="0" cellspacing="0" border="0"
          style="width:600px; max-width:600px; background-color:#FFFFFF; border:1px solid #FDE68A; border-radius:16px; overflow:hidden;">

          <!-- Header / logo -->
          <tr>
            <td align="center" class="gsb-px" style="padding:32px 40px 20px 40px; background-color:#FFFFFF;">
              <img src="{{ url('assets/img/logo.png') }}"
                width="64" height="64" alt="Goal Shot Ball Association of Bihar"
                style="display:block; height:64px; width:auto; margin:0 auto;">
              <div
                style="margin-top:14px; font-family:'Barlow Condensed', Arial, sans-serif; font-size:20px; font-weight:700; letter-spacing:0.4px; color:#0F172A; text-transform:uppercase;">
                Goal Shot Ball Association <span style="color:#F59E0B;">of Bihar</span>
              </div>
            </td>
          </tr>

          <!-- Gradient accent bar -->
          <tr>
            <td height="6" bgcolor="#F59E0B"
              style="height:6px; line-height:6px; font-size:0; background-color:#F59E0B; background-image:linear-gradient(135deg,#F59E0B 0%,#FBBF24 50%,#FCD34D 100%);">
              &nbsp;
            </td>
          </tr>

          <!-- Body -->
          <tr>
            <td class="gsb-px" style="padding:36px 40px 8px 40px;">
              @yield('content')
            </td>
          </tr>

          <!-- Footer -->
          <tr>
            <td class="gsb-px"
              style="padding:28px 40px 36px 40px; border-top:1px solid #FEF3C7; background-color:#FFF7E6;">
              <p
                style="margin:0 0 6px 0; font-family:'Barlow Condensed', Arial, sans-serif; font-size:16px; font-weight:700; color:#0F172A; text-transform:uppercase; letter-spacing:0.4px;">
                Goal Shot Ball Association of Bihar
              </p>
              <p style="margin:0 0 4px 0; font-family:Barlow, Arial, sans-serif; font-size:13px; line-height:20px; color:#78350F;">
                Kamruddinpur, Ward No-5, Begusarai (Bihar)
              </p>
              <p style="margin:0 0 14px 0; font-family:Barlow, Arial, sans-serif; font-size:13px; line-height:20px; color:#78350F;">
                <a href="tel:+918083319186" style="color:#2563EB; text-decoration:none;">8083319186</a>
                &nbsp;&middot;&nbsp;
                <a href="mailto:bihargoalshotball@gmail.com" style="color:#2563EB; text-decoration:none;">bihargoalshotball@gmail.com</a>
              </p>
              <p style="margin:0; font-family:Barlow, Arial, sans-serif; font-size:12px; line-height:18px; color:#A16207;">
                &copy; {{ date('Y') }} Goal Shot Ball Association of Bihar. All rights reserved.
              </p>
            </td>
          </tr>

        </table>
        <!-- /Card -->

      </td>
    </tr>
  </table>
</body>

</html>
