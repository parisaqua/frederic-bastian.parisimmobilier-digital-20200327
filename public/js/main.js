const articles = document.getElementById('articles');

if(articles) {
    articles.addEventListener('click', e => {
       
        if(e.target.className === 'fas fa-trash-alt') {
            
            if(confirm('Etes-vous certain ?')) {
                const id = e.target.getAttribute('data-id');
                
                fetch('/article/delete/${id}', {
                    method: 'DELETE'
                });
            }
        }
        
    });
}



