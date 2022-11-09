document.getElementById('btnNext').addEventListener('click', function () {
    let params = new URLSearchParams(window.location.search)
    let offset = parseInt(params.get('offsetPublic'), 10)
    offset += 10
    window.location.href = '/?offsetPublic=' + offset
})

document.getElementById('btnPrev').addEventListener('click', function () {
    let params = new URLSearchParams(window.location.search)
    let offset = parseInt(params.get('offsetPublic'), 10)
    offset -= 10
    if(offset < 0) {
        offset = 0
    }
    window.location.href = '/?offsetPublic=' + offset
})

document.getElementById('btnNextPrivate').addEventListener('click', function () {
    let params = new URLSearchParams(window.location.search)
    let offset = parseInt(params.get('offsetPublic'), 10)
    offset += 10
    window.location.href = '/?offsetPublic=' + offset
})

document.getElementById('btnPrevPrivate').addEventListener('click', function () {
    let params = new URLSearchParams(window.location.search)
    let offset = parseInt(params.get('offsetPrivate'), 10)
    offset -= 10
    if(offset < 0) {
        offset = 0
    }
    window.location.href = '/?offsetPrivate=' + offset
})