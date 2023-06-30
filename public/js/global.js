/*
 * Redirige vers page internet explorer si internet explorer
 */
var ua = window.navigator.userAgent;
var msie = ua.indexOf("MSIE ");
if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))  // If Internet Explorer, on modifie le margin right
{
    window.location.replace(base_url + "/internet_explorer");
}



/*
 * Permet de changer la classe du body au survol de la souris
 */
document.addEventListener('DOMContentLoaded', function () {
    let target = document.getElementById('sidenav-main');
    target.addEventListener("mouseover", mOver, false);
    target.addEventListener("mouseout", mOut, false);

    function mOver() {
        document.body.classList.remove("g-sidenav-hidden");
        document.body.classList.add("g-sidenav-show");
    }

    function mOut() {  
        document.body.classList.remove("g-sidenav-show");
        document.body.classList.add("g-sidenav-hidden");
    }
}, false);



/*
 * Equivalent a $(document).ready jquery
 */
function docReady(fn) {
    // see if DOM is already available
    if (document.readyState === "complete" || document.readyState === "interactive") {
        // call on next available tick
        setTimeout(fn, 1);
    } else {
        document.addEventListener("DOMContentLoaded", fn);
    }
}



/*
 * Submit le formulaire de logout
 */
function deconnexionButton()
{
    event.preventDefault();
    document.getElementById('logout-form').submit();
}



function userChange(){
    let change_users = document.getElementById('change_users');
    change_users.closest('form').submit();
}