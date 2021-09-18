if(document.querySelector('.sidebar')){
    let sidebar = document.querySelector('.sidebar')
    let sidebarNavigation = document.querySelector('.sidebar-navigation')
    let sidebarButton = document.getElementById('sidebar-button')
    let sidebarMobileButton = document.getElementById('sidebar-mobile-button')
    let wasClosed = sessionStorage.getItem('sidebar-status')

    sidebarMobileButton.onclick = () => {
        sidebar.classList.toggle('mobile-sidebar-open')
        sidebarMobileButton.classList.toggle('sidebar-button-open')
    }

    if(wasClosed === undefined || wasClosed === null) {
        sessionStorage.setItem('sidebar-status', false);
        sidebar.classList.contains('sidebar-closed') ? sidebar.classList.remove('sidebar-closed') : ''
    }

    if(wasClosed === 'true'){
        sidebar.classList.add('sidebar-closed')
    }

    sidebarButton.onclick = () => {
        sidebar.classList.toggle('sidebar-closed')
        wasClosed = !wasClosed
        sessionStorage.setItem('sidebar-status', wasClosed)
    }

    sidebarNavigation.onmouseenter = () => {
        if(sidebar.classList.contains('sidebar-closed')){
            sidebar.classList.remove('sidebar-closed')
        }
    }

    sidebarNavigation.onmouseleave = () => {
        if(wasClosed === 'true') {
            sidebar.classList.add('sidebar-closed')
        }
    }
}