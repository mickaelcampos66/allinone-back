const app = {

    init: function() {

        const mediaDelete = document.querySelectorAll('.mediaDelete');
        for (const media of mediaDelete) {
            media.addEventListener('click', app.handleClickOnDeleteButton)
        }

    },

    handleClickOnDeleteButton: function(e){

        if(!confirm('Etes-vous sûr de vouloir supprimer ce media?')){
            
            e.preventDefault();
        }
    }
}

document.addEventListener('DOMContentLoaded', app.init)