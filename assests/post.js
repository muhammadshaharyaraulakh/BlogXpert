const toggleCommentsBtn = document.getElementById('toggle-comments-btn');
const commentsSection = document.getElementById('comments-section');

toggleCommentsBtn.addEventListener('click', () => {
    commentsSection.classList.toggle('show');

    if (commentsSection.classList.contains('show')) {
        toggleCommentsBtn.innerHTML = '<i class="uil uil-angle-up"></i> Hide Comments';
    } else {
        toggleCommentsBtn.innerHTML = '<i class="uil uil-comment-alt-lines"></i> Show Comments';
    }
});

const openPopupBtn = document.getElementById('open-comment-popup');
    const commentPopup = document.getElementById('comment-popup');
    const closePopupBtn = document.getElementById('close-popup-btn');

    if (openPopupBtn && commentPopup && closePopupBtn) {
        openPopupBtn.addEventListener('click', () => {
            commentPopup.style.display = 'grid'; 
        });

        closePopupBtn.addEventListener('click', () => {
            commentPopup.style.display = 'none';
        });

        commentPopup.addEventListener('click', (e) => {
            if (e.target.id === 'comment-popup') {
                commentPopup.style.display = 'none';
            }
        });
    }

const likeForm = document.querySelector('.like-form');

if (likeForm) {
    likeForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        const formData = new FormData(likeForm);
        const likeBtn = likeForm.querySelector('.like-btn');
        const likeBtnSpan = likeBtn.querySelector('span');

        try {
            const response = await fetch('likehandler.php', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.status === 'success') {
                likeBtnSpan.textContent = data.likes + " Likes";
                likeBtn.disabled = true;
                likeBtn.classList.add('disabled-like');
                showToast(data.message, 'success'); 
            } else {
                showToast(data.message, 'error');
                if (data.message.includes("already liked")) {
                    likeBtn.disabled = true;
                    likeBtn.classList.add('disabled-like');
                }
            }
        } catch (error) {
            console.error('Error:', error);
            showToast("Connection failed.", "error"); 
        }
    });
}

const commentForm = document.querySelector('form[action="/pages/comment.php"]');

if (commentForm) {
    commentForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        const formData = new FormData(commentForm);
        const submitBtn = commentForm.querySelector('.comment-btn');
        const textarea = commentForm.querySelector('textarea[name="body"]');

        submitBtn.disabled = true;

        try {
            const response = await fetch(commentForm.getAttribute('action'), {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.status === 'success') {
                showToast(data.message, 'success');
                textarea.value = '';
                
                const commentsList = document.querySelector('.comments-list');
                if (commentsList && data.data) {
                    const article = document.createElement('article');
                    article.className = 'comment';
                    
                    const avatarSrc = data.data.avatar ? `/userImages/${data.data.avatar}` : `https://ui-avatars.com/api/?name=${encodeURIComponent(data.data.first_name)}&background=random`;
                    const formattedDate = new Date(data.data.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });

                    article.innerHTML = `
                        <div class="comment__avatar">
                            <img src="${avatarSrc}" alt="User">
                        </div>
                        <div class="comment__content">
                            <div class="comment__info">
                                <h5>${data.data.first_name}</h5>
                                <small>${formattedDate}</small> 
                            </div>
                            <p class="comment__body">
                                ${data.data.body.replace(/\n/g, '<br>')}
                            </p>
                            <form action="delete.php" method="POST" class="delete-comment-form">
                                <input type="hidden" name="comment_id" value="${data.data.id}">
                                <input type="hidden" name="post_id" value="${data.data.post_id}">
                                <button type="submit" class="delete-btn">
                                    <i class="uil uil-trash-alt"></i> Delete
                                </button>
                            </form>
                        </div>
                    `;
                    
                    commentsList.prepend(article);
                    
                    const headerH3 = document.querySelector('.comments-header h3');
                    if (headerH3) {
                        const match = headerH3.textContent.match(/\d+/);
                        if (match) {
                            headerH3.textContent = `Comments (${parseInt(match[0], 10) + 1})`;
                        }
                    }

                    const commentPopup = document.getElementById('comment-popup');
                    if (commentPopup) commentPopup.style.display = 'none';
                }
            } else {
                showToast(data.message, 'error');
            }

            submitBtn.disabled = false;

        } catch (error) {
            console.error(error);
            showToast("Connection failed.", 'error');
            submitBtn.disabled = false;
        }
    });
}


document.addEventListener('submit', async function (e) {
    if (e.target && e.target.classList.contains('delete-comment-form')) {
        e.preventDefault();

        const form = e.target;
        const commentCard = form.closest('.comment');
        const deleteBtn = form.querySelector('button');
        const formData = new FormData(form);

        deleteBtn.disabled = true;

        try {
            const response = await fetch(form.getAttribute('action'), {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.status === 'success') {
                showToast(data.message, 'success');

                if (commentCard) {
                    commentCard.style.transition = 'opacity 0.3s ease';
                    commentCard.style.opacity = '0';
                    setTimeout(() => commentCard.remove(), 300);
                }

                const headerH3 = document.querySelector('.comments-header h3');
                if (headerH3) {
                    const match = headerH3.textContent.match(/\d+/);
                    if (match) {
                        headerH3.textContent = `Comments (${parseInt(match[0], 10) - 1})`;
                    }
                }
            } else {
                showToast(data.message, 'error');
                deleteBtn.disabled = false;
            }

        } catch (error) {
            console.error(error);
            showToast("Connection failed.", 'error');
            deleteBtn.disabled = false;
        }
    }
});
