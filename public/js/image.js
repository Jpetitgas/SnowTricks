window.onload = () => {

    let links = document.querySelectorAll("[data-delete]")
    for (link of links) {
        link.addEventListener("click", function (e) {
            e.preventDefault()
            if (confirm("Voulez-vous vraiment supprimer cette image?")) {
                fetch(this.getAttribute("href"), {
                    method: "DELETE",
                    headers: {
                        "X-Requested-With": "XMLHttpRequest",
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({ "_token": this.dataset.token })
                }).then(
                    response => response.json()
                ).then(data => {
                    if (data.success)
                        this.parentElement.remove()
                    else
                        alert(data.error)
                }).catch(e => alert(e))
            }
        })
    }
    let pictures = document.querySelectorAll("[main-picture]")
    for (picture of pictures) {
        picture.addEventListener("click", function (f) {
            let cadres = document.querySelectorAll("[cadre]")
            for (cadre of cadres) {
                cadre.classList.value = "col";
            }
            var id = this.getAttribute("id")
            document.getElementById('figure_main').value = id
            var parent = this.parentNode.parentNode;
            parent.classList.value = "col-2 border border-dark";
        })
    }
}