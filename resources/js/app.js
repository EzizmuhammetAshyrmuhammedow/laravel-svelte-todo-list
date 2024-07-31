import { createInertiaApp } from "@inertiajs/svelte";
import "../css/bootstrap/bootstrap.css";
import "../css/bootstrap/bootstrap.js";
import "../css/app.css"
    import "../js/bulma-calendar.min"

createInertiaApp({
    id: "app",
    resolve: (name) => {
        // Import all Svelte components from the Pages directory
        const pages = import.meta.glob("./Pages/**/*.svelte", { eager: true });
        // Return the Svelte component that matches the name
        return pages[`./Pages/${name}.svelte`];
    },
    setup({ el, App, props }) {
        // Create and mount the Inertia app with the given props
        new App({ target: el, props });
    },
});
