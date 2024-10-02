function openPictureInPopup(url, windowName) {
    window.open(url, windowName, "popup");
};

const togglePassword = () => {
    const passwordInput = document.querySelector("#inputPassword")
    passwordInput.type = passwordInput.type === "text" ? "password" : "text"
    const eyeIcon = document.querySelector("#eye")
    eyeIcon.classList.contains("d-none") ? eyeIcon.classList.remove("d-none") : eyeIcon.classList.add("d-none")
    const eyeSlashIcon = document.querySelector("#eye-slash")
    eyeSlashIcon.classList.contains("d-none") ? eyeSlashIcon.classList.remove("d-none") : eyeSlashIcon.classList.add("d-none")
}

// Dark/White theme
window.onload=function(){
    let body = document.getElementById("mainBody");
    let whitebtn = document.querySelector('#btnWhite');
    let darkbtn = document.querySelector('#btnDark');

    // Vérifier s'il y a un thème stocké dans localStorage
    const storedTheme = localStorage.getItem('theme');
    if (storedTheme === 'dark') {
        body.classList.add(storedTheme); // Applique le thème stocké
        darkbtn.style.display = 'none';
        whitebtn.style.display = 'block';
    } else {
        body.classList.add('white') // Thème par défaut
        whitebtn.style.display = 'none';
        darkbtn.style.display = 'block';
    }
    
    darkbtn.addEventListener('click', () => {
        body.classList.add("dark");
        body.classList.remove("white");
        localStorage.setItem('theme', 'dark');
        darkbtn.style.display = 'none';
        whitebtn.style.display = 'block';
    })
    
    whitebtn.addEventListener('click', () => {
        body.classList.add("white");
        body.classList.remove("dark");
        localStorage.setItem('theme', 'white');
        whitebtn.style.display = 'none';
        darkbtn.style.display = 'block';
    })
}