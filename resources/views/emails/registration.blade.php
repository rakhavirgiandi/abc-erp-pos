<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAKOLA Email</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin="">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;700&display=swap" rel="stylesheet">
    <style>
@media (max-width: 576px) {
  .mail-container {
    max-width: 100%;
    margin: 16px;
  }

  .mail-content {
    padding: 24px 18px;
  }

  .body-email {
    padding: 16px;
  }

  .top-title h2 {
    font-size: 22px;
  }
}
</style>
</head>
<body style="margin: 0; background-color: #F1F4F4; font-family: 'Nunito', sans-serif; color: #383A36;">
    <div class="mail-container" style="max-width: 100%; margin: 24px auto; display: flex; flex-direction: column; justify-content: center; gap: 24px;">
        <div class="mail-content" style="padding: 10px 10px; background-color: #fff; border-radius: 16px; border: 1px solid #E6ECEC;">
            <div class="top-title" style="text-align: center;">
                <img src="{{ asset('assets/images/Sakola-Full-Colour.png') }}" alt="icon mail" style="margin-bottom: 4px; display: inline-block; width: 175px; object-fit: contain;" width="175">
                <p style="margin-top: 0px; margin: 0; font-size: 16px;">Software Keuangan dan Administrasi Sekolah</p>
                <br>
                <img src="{{ asset('assets-email/mail-icon.png') }}" alt="icon mail" style="margin-bottom: 4px; display: inline-block; height: 75px; object-fit: contain;" height="75">
                <h2 style="margin-top: 0px;">Hai, {{$name}}!</h2>
            </div>
            <div class="body-email" style="padding: 24px; border: 1px solid #E6ECEC; border-radius: 12px;">
                <div class="part">
                    <h4 style="margin-top: 0px; margin-bottom: 6px;">Selamat Datang di SAKOLA</h4>
                    <p style="margin-top: 0px;">Terimakasih telah mendaftar di Aplikasi SAKOLA. Silahkan untuk menjelajahi fitur SAKOLA. Jika Anda ingin kami lakukan demo atau presentasi, Anda dapat menghubungi <a href="https://api.whatsapp.com/send/?phone=6281908080017&text=halo+SAKOLA+,+jadwalkan+saya+presentasi&type=phone_number&app_absent=0" style="color: #FC7C3C; text-decoration: none;">Customer Service SAKOLA</a> untuk melakukan penjadwalan demo.</p>
                </div>
                <hr style="border: 1px solid #e6ecec; border-top: 0px;">
                <div class="part">
                    <h4 style="margin-top: 0px; margin-bottom: 6px;">Detail Akun SAKOLA Anda : </h4>
                    <table>
                        <tr>
                            <td>Email</td>
                            <td>:</td>
                            <td>{{$email}}</td>
                        </tr>
                        <tr>
                            <td>Password</td>
                            <td>:</td>
                            <td>{{$password}}</td>
                        </tr>
                    </table>
                    <p style="margin-top: 0px;">Harap berhati - hati, jangan bagikan password Anda kepada orang lain dan simpan email ini dengan baik. Terimakasih.</p>
                </div>
                <a href="https://app.sakola.id" class="mail-button" style="padding: 12px 24px; display: block; background-color: #FC7C3C; color: #fff; border-radius: 8px; text-align: center; text-decoration: none;">Login SAKOLA</a>
            </div>
            <div class="mail-footer" style="text-align: center; color: #919290;">
                <div class="text-center">
                    <small style="font-size: 13px;">Download aplikasi SAKOLA</small>
<table style="margin-left: auto; margin-right: auto;">
                        <tr>
                            <td><a href="https://play.google.com/store/apps/details?id=id.sakola.apps" style="color: #FC7C3C; display: block; height: 40px; width: auto; text-decoration: none;"><img src="{{ asset('assets-email/btn-playstore.png') }}" alt="playstore" style="width: 100%; height: 100%; object-fit: contain;"></a></td>
                            <td><a href="#" style="color: #FC7C3C; display: block; height: 40px; width: auto; text-decoration: none;"><img src="{{ asset('assets-email/btn-appstore.png') }}" alt="appstore" style="width: 100%; height: 100%; object-fit: contain;"></a></td>
                        </tr>
                    </table>
                </div>
                <small style="font-size: 13px;">E-mail ini dibuat secara otomatis, mohon tidak membalas. Jika butuh bantuan, silahkan
                    hubungi <a href="https://api.whatsapp.com/send/?phone=6281908080017&text=halo+SAKOLA+,+jadwalkan+saya+presentasi&type=phone_number&app_absent=0" style="color: #FC7C3C; text-decoration: none;">CS SAKOLA</a>.<br>
                    Copyright ©{{date('Y')}} <a href="https://sakola.id" style="color: #FC7C3C; text-decoration: none;">sakola.id</a></small>
            </div>
        </div>
    </div>
</body>
</html>