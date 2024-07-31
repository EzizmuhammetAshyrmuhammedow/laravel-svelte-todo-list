<script>
    import { page, Link, useForm } from "@inertiajs/svelte";
    import GuestLayout from "../Layouts/GuestLayout.svelte";
    import MainLayout from "../Layouts/Authenticated.svelte";
    import { onMount } from "svelte";

    $: success = $page.props.flash.success;
    $: isLoggedIn = $page.props.isLoggedIn;

    let showSuccess = false;

    onMount(() => {
        if (success) {
            showSuccess = true;
            setTimeout(() => {
                showSuccess = false;
            }, 5000); // Hide after 5 seconds
        }
    });
</script>

<svelte:head>
    <title>Simple To-Do List</title>
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65"
        crossorigin="anonymous"
    />
</svelte:head>

{#if showSuccess}
    <div class="alert alert-success" role="alert">
        {success}
    </div>
{/if}
{#if isLoggedIn === true}
    <MainLayout />
{:else}
    <GuestLayout />
{/if}
