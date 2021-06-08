const users = document.getElementById('users');

if(users){
users.addEventListener('click', e => {
    if(e.target.className === 'btn btn-danger delete-user')
    {
        if(confirm('Are you sure ?'))
        {
            const id = e.target.getAttribute('data-id');
            
            fetch(`/user/delete/${id}`,{
                method: 'DELETE'
            }).then(window.location.reload());
        }
    }
});
}