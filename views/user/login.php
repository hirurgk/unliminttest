<div class="col-sm-12">
    <h1 class="d-flex justify-content-center">Вход</h1>

    <div class="d-flex justify-content-center">
        <form action="/user/login" method="POST" class="login-form">
            <div class="errors"></div>

            <div class="form-label-group">
                <input type="login" name="login" id="inputLogin" class="form-control" placeholder="Логин" >
            </div> 

            <div class="form-label-group">
                <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Пароль">
            </div>

            <button class="btn btn-lg btn-dark btn-block btn-login text-uppercase font-weight-bold mb-2" type="submit">Войти</button>
        </form>
    </div>
</div>