function checkLogin() {
    const token = localStorage.getItem('token');
    if (!token) {
        window.location.href = '../authenticate/login.html';
    }
}

checkLogin();

function logout() {
    localStorage.removeItem('token');
    localStorage.removeItem('role');
    localStorage.removeItem('auth_user');
    window.location.href = '../authenticate/login.html';
}