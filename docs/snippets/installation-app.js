import { registerApril, registerLivewireBridge } from '../../vendor/yungifez/april-ui/resources/js/april-core.js'

document.addEventListener('alpine:init', () => {
    registerApril(window.Alpine)
    registerLivewireBridge(window.Alpine)
})
