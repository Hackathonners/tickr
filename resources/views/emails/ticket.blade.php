<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="box-sizing: border-box; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14px; margin: 0;">
<head style="box-sizing: border-box; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14px; margin: 0;">
  <meta name="viewport" content="width=device-width" style="box-sizing: border-box; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14px; margin: 0;">
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" style="box-sizing: border-box; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14px; margin: 0;">
  <title style="box-sizing: border-box; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14px; margin: 0;"></title>
  
</head>
<body itemscope="" itemtype="http://schema.org/EmailMessage" style="-webkit-font-smoothing: antialiased; -webkit-text-size-adjust: none; background-color: #f6f6f6; box-sizing: border-box; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14px; height: 100%; line-height: 1.6em; margin: 0; width: 100% !important;">
  <table class="body-wrap" style="background-color: #f6f6f6; box-sizing: border-box; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14px; margin: 0; width: 100%;">
    <tr style="box-sizing: border-box; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14px; margin: 0;">
      <td class="content-wrap aligncenter logo" style="box-sizing: border-box; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14px; margin: 0; padding: 20px; text-align: center; vertical-align: top;">
      </td>
    </tr>
    <tr style="box-sizing: border-box; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14px; margin: 0;">
      <td class="container" width="600" style="box-sizing: border-box; clear: both !important; display: block !important; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14px; margin: 0 auto !important; max-width: 600px !important; vertical-align: top;">
        <div class="content" style="box-sizing: border-box; display: block; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14px; margin: 0 auto; max-width: 600px; padding: 20px;">
          <table class="main" width="100%" cellpadding="0" cellspacing="0" style="background-color: #fff; border: 1px solid #e9e9e9; border-radius: 3px; box-sizing: border-box; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14px; margin: 0;">
            <tr style="box-sizing: border-box; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14px; margin: 0;">
              <td class="content-wrap aligncenter" style="box-sizing: border-box; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14px; margin: 0; padding: 20px; text-align: center; vertical-align: top;">
                <table width="100%" cellpadding="0" cellspacing="0" style="box-sizing: border-box; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14px; margin: 0;">

                  <tr style="box-sizing: border-box; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14px; margin: 0;">
                    <td class="content-block" style="box-sizing: border-box; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14px; margin: 0; padding: 0 0 20px; vertical-align: top;">
                      <h2 class="aligncenter" style="box-sizing: border-box; color: #000; font-family: 'Helvetica Neue', Helvetica, Arial, 'Lucida Grande', sans-serif; font-size: 24px; font-weight: 400; line-height: 1.2em; margin: 40px 0 0; margin-bottom: 10px; text-align: center;">{{ $registration->event->title }}</h2>
                      <p class="aligncenter" style="box-sizing: border-box; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14px; font-weight: normal; margin: 0; margin-bottom: 10px; margin-top: 10px; text-align: center;">Starts on {{ $registration->event->start_at->format('l, jS \\of F Y \a\t H:i') }}</p>
                    </td>
                  </tr>
                  <tr style="box-sizing: border-box; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14px; margin: 0;">
                    <td class="content-block aligncenter" style="box-sizing: border-box; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14px; margin: 0; padding: 0 0 20px; text-align: center; vertical-align: top;">
                      <div class="ticket-container" style="background-color: #f6f6f6; border-radius: 3px; box-sizing: border-box; clear: both !important; display: block !important; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14px; margin: 0 auto !important; max-width: 400px !important; padding: 45px;">
                        <img src="ticket.jpg" style="box-sizing: border-box; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14px; margin: 0; max-width: 100%;">
                      </div>
                      <!-- src="$message->embedData(QrCode::format('png')->size(500)->generate($activationUrl), 'ticket.png')" -->
                    </td>
                  </tr>
                  <tr style="box-sizing: border-box; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14px; margin: 0;">
                    <td class="content-block aligncenter" style="border-bottom: #eee 1px solid; box-sizing: border-box; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14px; margin: 0; padding: 0 0 20px; padding-bottom: 40px; text-align: center; vertical-align: top;">
                      <strong style="box-sizing: border-box; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14px; margin: 0;">Thank you for purchasing a ticket to this event.<br style="box-sizing: border-box; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14px; margin: 0;">
                      Just show this e-mail at the entrance<br style="box-sizing: border-box; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14px; margin: 0;">
                        and you're good to go!</strong>
                      </td>
                    </tr>
                    <tr style="box-sizing: border-box; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14px; margin: 0;">
                      <td class="content-block aligncenter" style="box-sizing: border-box; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14px; margin: 0; padding: 0 0 20px; text-align: center; vertical-align: top;">
                        <table class="invoice" style="box-sizing: border-box; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14px; margin: 30px auto; padding-top: 20px; text-align: left; width: 80%;">
                          <tr style="box-sizing: border-box; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14px; margin: 0;">
                            <td style="box-sizing: border-box; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14px; margin: 0; padding: 5px 0; padding-bottom: 20px; vertical-align: top;"><strong style="box-sizing: border-box; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14px; margin: 0;">Name: </strong>{{ $registration->user->name }}
                            </td></tr>
                            <tr style="box-sizing: border-box; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14px; margin: 0;">
                              <td colspan="2" style="box-sizing: border-box; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14px; margin: 0; padding: 5px 0; vertical-align: top;">
                                <table class="invoice-items" cellpadding="0" cellspacing="0" style="box-sizing: border-box; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14px; margin: 0; width: 100%;">
                                  <thead class="table-head" style="box-sizing: border-box; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14px; margin: 0;">
                                    <th style="border-bottom: 2px solid #2BB074; box-sizing: border-box; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14px; margin: 0;">
                                      Description
                                    </th>
                                    <th style="border-bottom: 2px solid #2BB074; box-sizing: border-box; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14px; margin: 0;">
                                      Value
                                    </th>
                                  </thead>
                                  <tbody style="box-sizing: border-box; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14px; margin: 0;">
                                    <tr style="box-sizing: border-box; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14px; margin: 0;">
                                      <td style="border-top: #eee 1px solid; box-sizing: border-box; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14px; margin: 0; padding: 5px 0; vertical-align: top;">Ticket "{{ $registration->registrationType->type }}"</td>
                                      <td style="border-top: #eee 1px solid; box-sizing: border-box; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14px; margin: 0; padding: 5px 0; vertical-align: top;">€{{ $registration->registrationType->price }}</td>
                                    </tr>
                                    @if($registration->fined)
                                    <tr style="box-sizing: border-box; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14px; margin: 0;">
                                      <td style="border-top: #eee 1px solid; box-sizing: border-box; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14px; margin: 0; padding: 5px 0; vertical-align: top;">Fine</td>
                                      <td style="border-top: #eee 1px solid; box-sizing: border-box; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14px; margin: 0; padding: 5px 0; vertical-align: top;">€{{ $registration->registrationType->fine }}</td>
                                    </tr>
                                    @endif
                                    <tr style="box-sizing: border-box; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14px; margin: 0;">
                                      <td style="border-top: #eee 1px solid; box-sizing: border-box; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14px; margin: 0; padding: 5px 0; vertical-align: top;"><strong style="box-sizing: border-box; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14px; margin: 0;">Total</strong></td>
                                      <td style="border-top: #eee 1px solid; box-sizing: border-box; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14px; margin: 0; padding: 5px 0; vertical-align: top;"><strong style="box-sizing: border-box; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14px; margin: 0;">€{{ $registration->total }}</strong></td>
                                    </tr>
                                  </tbody>
                                </table>
                              </td>
                            </tr>
                          </table>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>
              <div class="footer" style="box-sizing: border-box; clear: both; color: #999; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14px; margin: 0; padding: 20px; width: 100%;">
                <table width="100%" style="box-sizing: border-box; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14px; margin: 0;">
                  <tr style="box-sizing: border-box; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 14px; margin: 0;">
                    <td class="aligncenter content-block" style="box-sizing: border-box; color: #999; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 12px; margin: 0; padding: 0 0 20px; text-align: center; vertical-align: top;">Built with &hearts; by <a href="http://hackathonners.org" style="box-sizing: border-box; color: #999; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 12px; margin: 0; text-decoration: underline;">Hackathonners</a></td>
                  </tr>
                </table>
              </div>
            </div>
          </td>
        </tr>
      </table>
    </body>
    </html>
