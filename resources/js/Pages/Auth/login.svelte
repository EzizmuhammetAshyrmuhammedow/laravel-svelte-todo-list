<script>
    import { Link } from "@inertiajs/svelte";
    import { useForm } from "@inertiajs/svelte";
    import { page } from "@inertiajs/svelte";

    let form = useForm({
        email: "",
        password: "",
    });

    function submit() {
        $form.post("/login"), {
            preserveState: true,
            preserveScroll: true,
        };
    }

    $: message = $page.props.flash.success || $page.props.flash.error;
    $: messageType = $page.props.flash.success ? "success" : "error";
</script>

<html lang="en" data-bs-theme="dark">
    <body>
        <div
            style="height: 100vh"
            class="w-100 d-flex justify-content-center align-items-center"
        >
            {#if message}
                <div class={`alert alert-${messageType}`} role="alert">
                    {message}
                </div>
            {/if}
            <form
                class="w-25 h-auto p-3 border border-secondary rounded-2"
                on:submit|preventDefault={submit}
            >
                <h3 class="mb-4">Log In</h3>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input
                        type="email"
                        class="form-control"
                        id="email"
                        name="email"
                        placeholder="example@bla.com"
                        autocomplete="email"
                        required
                        bind:value={$form.email}
                    />
                    {#if $form.errors.email}
                        <div class="text-danger">{$form.errors.email}</div>
                    {/if}
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input
                        type="password"
                        class="form-control"
                        id="password"
                        name="password"
                        placeholder="•••••••••••"
                        autocomplete="new-password"
                        required
                        bind:value={$form.password}
                    />
                    {#if $form.errors.password}
                        <div class="text-danger">{$form.errors.password}</div>
                    {/if}
                </div>
                <div class="d-flex flex-row gap-1 mb-3">
                    <p class="mb-0">Don't have an account?</p>
                    <Link href="/signup">Create an Account</Link>
                </div>
                <button type="submit" class="btn btn-primary w-100"
                    >Submit</button
                >
                <div id="response" class="mt-3"></div>
            </form>
        </div>
    </body>
</html>
