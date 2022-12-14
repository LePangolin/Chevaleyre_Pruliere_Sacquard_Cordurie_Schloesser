let params = new URLSearchParams(window.location.search)
let offsetPublic = params.has('offsetPublic')?parseInt(params.get('offsetPublic'), 10):0
let offsetPrivate = params.has('offsetPrivate')?parseInt(params.get('offsetPrivate'), 10):0

document.getElementById('btnNext').addEventListener('click', function () {
    offsetPublic += 15
    window.location.href = '/?offsetPublic=' + offsetPublic + '&offsetPrivate=' + offsetPrivate
})

document.getElementById('btnPrev').addEventListener('click', function () {
    if (offsetPublic > 0) {
        offsetPublic -= 15
        window.location.href = '/?offsetPublic=' + offsetPublic + '&offsetPrivate=' + offsetPrivate
    }
})

if (document.body.contains(document.getElementById('btnNextPrivate'))) {
    document.getElementById('btnNextPrivate').addEventListener('click', function () {
        offsetPrivate += 15
        window.location.href = '/?offsetPublic=' + offsetPublic + '&offsetPrivate=' + offsetPrivate
    })
}

if(document.body.contains(document.getElementById('btnPrevPrivate'))) {
    document.getElementById('btnPrevPrivate').addEventListener('click', function () {
        if (offsetPrivate > 0) {
            offsetPrivate -= 15
            window.location.href = '/?offsetPublic=' + offsetPublic + '&offsetPrivate=' + offsetPrivate
        }
    })
}

document.querySelectorAll('.gallery-vign').forEach(function (element) {
    element.addEventListener('click', function () {
        let id = element.dataset.id
        window.location.href = '/gallery/' + id
    })
})
