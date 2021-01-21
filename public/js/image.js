window.onload = () => {

    let links = document.querySelectorAll("[data-delete]")
    for (link of links) {
        link.addEventListener("click", function (e) {
            e.preventDefault()
            if (confirm("Voulez-vous vraiment supprimer cette image?")) {
                document.getElementById("loader").setAttribute("style", "display:block");
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
                    document.getElementById("loader").setAttribute("style", "display:none");
                    if (data.success)
                        this.parentElement.parentElement.parentElement.remove()

                    else
                        alert(data.error)
                }).catch(e => alert(e))
                
            }
            
        })
    }
    let pictures = document.querySelectorAll("[main-picture]")
    for (picture of pictures) {
        picture.addEventListener("click", function (f) {
            let checks = document.querySelectorAll("[check]")
            for (check of checks) {
                
                if (check.getAttribute("image") == this.getAttribute("image")){
                    check.innerHTML = '<i class="fa fa-check" aria-hidden="true"></i>';
                } else {
                    check.innerHTML = "";
                }               
            }
            
            var id = this.getAttribute("id")
            document.getElementById('figure_main').value = id
            
        })
    }
}