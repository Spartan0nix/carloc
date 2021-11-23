if(document.querySelector('.admin_crud_read')){
    let delete_buttons = document.querySelectorAll('.action-delete')
    delete_buttons.forEach(element => {
        element.addEventListener('click', (event) => {
            let user_choice = confirm('Confirmer la suppressions de cette élément ?')
            if(!user_choice){
                event.preventDefault()
            }
        })
    });
}