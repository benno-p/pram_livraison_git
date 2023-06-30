let color = '';
let text = '';
let textHover = '';
const csrf = document.querySelector('meta[name="csrf-token"]').content;
let user = JSON.parse(document.querySelector("#app").dataset.user);
docReady(function() {

    if(user.authenticated === true){
        let body = document.getElementsByTagName('body')[0];
        let sidebar = document.getElementById('sidebar');
        let sidebarMenu = document.getElementById('sidebarMenu');
        let navItem = sidebar.getElementsByClassName('menu-deroulant');
        let base_height = 0;
        let sidebar_ul = document.getElementById('sidebar_ul');
        let header = document.getElementById('header');
        let profileMenu = document.getElementById('profileMenu'); 


        if(window.screen.availWidth <= 765){
            sidebar.style.width = 0 + 'px';
            sidebarMenu.style.display = 'none';
        }

        // color = user.sidebar_color ? user.sidebar_color : '#343a40';
        // text = user.sidebar_text_color ? user.sidebar_text_color : '#cfcfcf';
        // textHover = user.sidebar_hover_text_color ? user.sidebar_hover_text_color : '#72d416';
        color = '#343a40';
        text = '#cfcfcf';
        textHover = '#72d416';

        changeSidebarColor(color);
        changeSidebarTextColor(text);
        changeSidebarHoverTextColor(textHover);

        changeMenu();
        displayScrollBar();

        [].forEach.call(document.getElementsByClassName('menu-deroulant'), function(el) {
            el.addEventListener('click', function() {
                let nav_item_slidedown = this.nextElementSibling;
                menuDeroulant(this, nav_item_slidedown);
                    displayScrollBar();
            }, false)
        });


        const menuButton = document.getElementById('toggleMenuIcon');
        menuButton.addEventListener('click', function(){
            if(sidebar.style.width == 0+'px' || sidebar.style.width == 0){
                sidebar.style.width = 250 + 'px';
                sidebarMenu.style.display = 'block';
            }else{
                sidebar.style.width = 0;
                sidebarMenu.style.display = 'none';
            }
        })
    }
});


function changeSidebarColor(new_color)
{
    if(user.authenticated === false){
        return false;
    }

    let sidebar = document.getElementById('sidebar');
    color = new_color;
    // document.getElementById('sidebar_color').value = new_color;
    sidebar.style.backgroundColor = new_color;
    header.style.backgroundColor = new_color;
}

function changeSidebarTextColor(new_color)
{
    if(user.authenticated === false){
        return false;
    }

    let sidebar = document.getElementById('sidebar');
    text = new_color;
    // document.getElementById('sidebar_text_color').value = new_color;
    sidebar.style.color = new_color
    header.style.color = new_color;
    // profileMenu.style.color = new_color;

    let anchors = sidebar.getElementsByTagName('a');

    for(let i = 0; i < anchors.length; i++){
        let anchor = anchors[i];
        anchor.style.color = new_color;
        anchor.addEventListener("mouseout", mOut, false);

        function mOut() {  
           anchor.setAttribute("style", "color:"+new_color+";")
        }
    }
}

function changeSidebarHoverTextColor(new_color)
{
    if(user.authenticated === false){
        return false;
    }

    let sidebar = document.getElementById('sidebar');
    textHover = new_color;
    let navItem = sidebar.getElementsByClassName('menu-deroulant');
    // document.getElementById('sidebar_text_hover_color').value = new_color;

    let anchors = sidebar.getElementsByTagName('a');

    for(let i = 0; i < anchors.length; i++){
        let anchor = anchors[i];
        anchor.addEventListener("mouseover", mOver, false);

        function mOver() {
           anchor.setAttribute("style", "color:"+new_color+";")
        }
    }


    for(let i = 0; i < navItem.length; i++){
        let menuDeroulant = navItem[i];
        if(menuDeroulant.classList.contains(' active')){
            
        }
    }
}


// function saveUserSidebarColumn()
// {
//     (async () => {
//         const rawResponse = await fetch(base_url + '/user_sidebar_color', {
//             method: 'POST',
//             headers: {
//                 'Accept': 'application/json',
//                 'Content-Type': 'application/json',
//                 "X-CSRF-Token": csrf
//             },
//             body: JSON.stringify({color: color, text: text, textHover: textHover})
//         });
//         const content = await rawResponse.json();

//         console.log(content);
//     })();

// }

function resetDefautColor()
{
    color = '#343a40';
    text = '#cfcfcf';
    textHover = '#72d416';
    changeSidebarColor(color);
    changeSidebarTextColor(text)
    changeSidebarHoverTextColor(textHover)
    saveUserSidebarColumn();
}


function displayScrollBar()
{

    setTimeout(function(){
        if(sidebarMenu.offsetHeight >= 500){
            sidebar_ul.style.overflowY = "scroll";
        }else{
           sidebar_ul.style.overflowY = "hidden"; 
        }
    }, 500)
}


function menuDeroulant(item, nav_item_slidedown)
{                
    let lis = nav_item_slidedown.getElementsByTagName('li');
    let height = 0;

    for(let i = 0; i < lis.length; i++){
        let li = lis[i];
        el_height = window.getComputedStyle(li, null).getPropertyValue("height");
        height = height + parseInt(el_height);
    }

    if(!nav_item_slidedown.classList.contains('active')){
        nav_item_slidedown.classList.add('active');
        if(nav_item_slidedown.id != 'sidebar_ul'){
           nav_item_slidedown.style.height = height + 'px'; 
        }
    }else{
        nav_item_slidedown.classList.remove('active');
        nav_item_slidedown.style.height = 0 + 'px';
    }
}



function changeMenu(url = null)
{
    if(user.authenticated === false){
        return false;
    }

    let sidebar = document.getElementById('sidebar');
    let anchors = sidebar.getElementsByTagName('a');

    if(window.screen.availWidth <= 765){
        sidebar.style.width = 0 + 'px';
        sidebarMenu.style.display = 'none';
    }

    // On remet toutes les a href de la couleur de base du text
    for(let i = 0; i < anchors.length; i++){
        let anchor = anchors[i];
        anchor.addEventListener("mouseover", mOver, false);
        anchor.addEventListener("mouseout", mOut, false);
        anchor.setAttribute("style", "color:"+text+";")
    }

    let selector = sidebar.getElementsByTagName('a');
    for (var i = 0; i < selector.length; i++) {
        let item = selector[i];

        if(item.closest('li')){
            item.closest('li').classList.remove('active');
            if(item.closest('li').previousElementSibling != null){
                item.closest('li').previousElementSibling.classList.remove('active');
            }
            if(item.closest('ul').previousElementSibling != null){
                item.closest('ul').previousElementSibling.classList.remove('active');
            }

            if(window.location.href == item.href && url == null){
                setTimeout(function(){
                    item.closest('li').classList.add('active');
                    if(item.closest('li').closest('ul').previousElementSibling != null){
                        item.closest('li').closest('ul').previousElementSibling.classList.add('active');
                        item.style.color = textHover;
                        actual_link = item;   
                    }else{
                        item.style.color = textHover;
                        actual_link = item;
                    }
                    
                }, 0.1)

                let nav_item_slidedown = item.closest('ul');
                if(!nav_item_slidedown.classList.contains('active')){
                    menuDeroulant(item, nav_item_slidedown)
                }
            }else if(url != null && item.href == base_url + '/' +url){
                setTimeout(function(){
                    item.closest('li').classList.add('active');
                    if(item.closest('li').closest('ul').previousElementSibling){
                        item.closest('li').closest('ul').previousElementSibling.classList.add('active');
                        item.style.color = textHover;
                    }
                    
                }, 0.1)

                let nav_item_slidedown = item.closest('ul');
                if(!nav_item_slidedown.classList.contains('active')){
                    menuDeroulant(item, nav_item_slidedown)
                }
            }
        }
    }

    window.scrollTo({
        top: 0,
        left: 0,
        behavior: 'smooth'
    });

    displayScrollBar();

    function mOver() {
        if (typeof actual_link !== 'undefined') {
            actual_link.setAttribute("style", "color:"+textHover+";");
        }
        
    }

    function mOut() {
        if (typeof actual_link !== 'undefined') {
            actual_link.setAttribute("style", "color:"+textHover+";");
        }
    }



    let sideIconToggle = document.getElementById('toggleMenuIcon');

    document.addEventListener('click', function(event) {
        if(window.screen.availWidth <= 765 && !sidebar.contains(event.target) && !sideIconToggle.contains(event.target)){
            sidebar.style.width = 0;
            sidebarMenu.style.display = 'none';  
        }
    })

    // parseInt(this.style.width,10)

    // function testFunction(){
    //     let test = 'oui'
    //     document.getElementById("sidebar").onmousedown = function () {
    //         let test = 'non'
    //         console.log('la')
                
    //         return test;
    //         return true;
    //     };
    //     return test;
    //     return false;
    // }

    // function printMousePos(event) {
        
    //     // console.log("clientX: " + event.clientX)
    //     // console.log("- clientY: " + event.clientY)
    //     let test = testFunction(); 

    //     console.log(test);       

    //     if(test == 'oui' && sidebar.style.width > 0){
    //         sidebar.style.width = 0;
    //         sidebarMenu.style.display = 'none';
    //     }


    // }

    // document.addEventListener("click", printMousePos);


}