<script>
    import { page } from "@inertiajs/svelte";
    import { createEventDispatcher } from "svelte";

    $: isLoggedIn = $page.props.isLoggedIn;

    async function logout() {
        const response = await fetch("/logout", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
            },
            credentials: "include",
        });

        if (response.redirected) {
            window.location.href = response.url;
        }
    }

    let isOpen = false;

    const dispatch = createEventDispatcher();

    function closeModal() {
        isOpen = false;
        dispatch("close");
    }
</script>

<button
    class="modal-button button"
    data-target="modal"
    on:click={() => (isOpen = true)}>Logout</button
>

<div class="modal" class:is-active={isOpen}>
    <!-- svelte-ignore a11y-click-events-have-key-events -->
    <!-- svelte-ignore a11y-no-static-element-interactions -->
    <div class="modal-background" on:click={closeModal} />
    <div class="modal-card">
        <header class="modal-card-head">
            <p class="modal-card-title">Confirm Logout</p>
        </header>
        <section class="modal-card-body">
            <p>Are you sure about this?</p>
        </section>
        <footer class="modal-card-foot">
            <div class="buttons">
                <button class="button" on:click={closeModal}>Cancel</button>
                <button class="button is-success" on:click={logout}
                    >Confirm</button
                >
            </div>
        </footer>
        <button class="modal-close" aria-label="close" on:click={closeModal} />
    </div>
</div>
