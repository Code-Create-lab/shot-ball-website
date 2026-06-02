@php($isLast = $last ?? false)
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
  <tr>
    <td style="padding:10px 0; {{ $isLast ? '' : 'border-bottom:1px solid #FDE68A;' }}">
      <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
          <td class="gsb-stack" width="40%" valign="top"
            style="font-family:Barlow, Arial, sans-serif; font-size:13px; line-height:20px; color:#92400E; text-transform:uppercase; letter-spacing:0.5px;">
            {{ $label }}
          </td>
          <td class="gsb-stack" width="60%" valign="top"
            style="font-family:Barlow, Arial, sans-serif; font-size:15px; line-height:22px; font-weight:600; color:#0F172A;">
            {{ $value !== '' && $value !== null ? $value : '—' }}
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
