function clearZoloStorage() {
    if (typeof localStorage !== 'undefined') {
        // check cachedBlocks is set
        if (localStorage.getItem('cachedBlocks') !== null) {
            localStorage.removeItem('cachedBlocks');
        }

        // check cachedExtensions is set
        if (localStorage.getItem('cachedExtensions') !== null) {
            localStorage.removeItem('cachedExtensions');
        }
    }
}

clearZoloStorage();
