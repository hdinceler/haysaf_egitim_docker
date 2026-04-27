

<h1 class="center large">Kayıt Ol</h1>
<form method="post" id="registerForm"class="card container large margin-top padding light-green ">
    <input type="hidden" name="csrf_token" value="<?=htmlspecialchars(SECURITY::generateCsrfToken(), ENT_QUOTES)?>">

    <label for="email">E-posta</label>
    <input type="text" name="email" class="input border border-green" value="deneme@deneme.com" required>
    <label for="password1">Şifre</label>
    <input type="text" name="password1" class="input border border-green" value="A12345+" required>

     <label for="password2">Şifre Tekrar</label>
    <input type="text" name="password2" class="input border border-green" value="A12345+" required>

    <button type="submit" class="button green block margin-top">Kayıt Ol</button>
</form>


<h1 class="center large">Giriş Yap</h1>
<form method="post"  id="loginForm"  class="container large margin-top padding light-green ">
    <input type="hidden" name="csrf_token" value="<?=htmlspecialchars(SECURITY::generateCsrfToken(), ENT_QUOTES)?>">

    <label for="email">E-posta</label>
    <input type="text" name="email" value="bilgi@haysaf.com" class="input border border-green" required>
    <label for="password">Şifre</label>
    <input type="text" name="password" value="babator2" class="input border border-green" required>

    <label class=""> 
        <input type="checkbox" name="remember" class="check" value="1">
        Beni hatırla
    </label>

    <button type="submit" class="button green block margin-top">Giriş Yap</button>
</form>

<script>
    const registerForm= document.getElementById('registerForm');
    registerForm.addEventListener('submit', async (e) => {
        e.preventDefault();     
        const formData = new FormData(registerForm);
        const response = await fetch('/api/register.php', {
            method: 'POST',
            body: JSON.stringify(Object.fromEntries(formData)),
            headers: {
                'Content-Type': 'application/json'            }
        });
        const result = await response.json();
        if (result.success) {
            alert('Kayıt başarılı!');
            // window.location.href = '/profilim'; // Anasayfaya yönlendir
        } else {
            alert('Kayıt başarısız: ' + result.message);
        }
    });


    const loginForm = document.getElementById('loginForm');
    loginForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(loginForm);
        const response = await fetch('/api/login.php', {
            method: 'POST',
            body: JSON.stringify(Object.fromEntries(formData)),
            headers: {
                'Content-Type': 'application/json'
            }
        });
        const result = await response.json();
        if (result.success) {
            alert('Giriş başarılı!');
            // window.location.href = '/profilim'; // Anasayfaya yönlendir
        } else {
            alert('Giriş başarısız: ' + result.message);
        }
    });
</script>