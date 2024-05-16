function updatePostPosition() {
    var postElement = document.querySelector('.post');
    if (postElement) {
        postElement.style.right = (window.innerWidth - 250) + 'px';
        postElement.style.top = (130) + 'px';
    }
}

window.addEventListener('load', updatePostPosition);
window.addEventListener('resize', updatePostPosition);


function togglePostIt(button) {
    const post = button.parentElement.parentElement;
    const ul = post.querySelector('ul');
    if (ul.classList.contains('hidden')) {
        ul.classList.remove('hidden');
        button.textContent = '-';
    } else {
        ul.classList.add('hidden');
        button.textContent = '+';
    }
}