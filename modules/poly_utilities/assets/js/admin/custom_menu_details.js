window.addEventListener('load', function () {
    (function ($) {
        "use strict";

        PolyCustomMenu.Init();

        const { createApp, ref, watch, onMounted, nextTick } = Vue

        const app = createApp({

            setup() {
                const dataLoaded = ref(false);
                const isProccessing = ref(false);
                const iframeCount = ref(0);
                const loadedCount = ref(0);

                 function checkAllIframesLoaded() {
                    if (loadedCount.value >= iframeCount.value && iframeCount.value > 0) {
                        dataLoaded.value = true;
                    }
                }
        
                onMounted(() => {
                    const iframes = document.querySelector('#polyApp').getElementsByTagName('iframe');
                    iframeCount.value = iframes.length;
                    Array.from(iframes).forEach(iframe => {
                        iframe.addEventListener('load', () => {
                            loadedCount.value++;
                            checkAllIframesLoaded();
                        });
                    });
                });

                return {
                    dataLoaded,
                    isProccessing,
                };
            }

        }).mount('#polyApp');
    })(jQuery);
});