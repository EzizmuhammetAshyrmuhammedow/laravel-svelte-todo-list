<script>
    import { Link } from "@inertiajs/svelte";
    import { useForm } from "@inertiajs/svelte";
    import { page } from "@inertiajs/svelte";

    let form = useForm({
        name: "",
        email: "",
        password: "",
    });

    function submit() {
        $form.post("/signup"),
            {
                preserveState: true,
                preserveScroll: true,
            };
    }

    $: message = $page.props.flash.success || $page.props.flash.error;
    $: messageType = $page.props.flash.success ? "success" : "error";
</script>

<html lang="en" data-bs-theme="dark" style="height: 100vh">
    <body>
        <div
            style="height: 100vh"
            class="w-100 d-flex justify-content-center align-items-center"
        >
            <form
                action=""
                class="w-25 h-auto p-3 border border-secondary rounded-2"
                on:submit|preventDefault={submit}
            >
                <h3>Create an Account</h3>
                <div class="mb-3">
                    <label for="user_name" class="form-label">Username</label>
                    <input
                        type="text"
                        class="form-control"
                        id="user_name"
                        placeholder="johnDoe_077"
                        autocomplete="username"
                        bind:value={$form.name}
                    />
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input
                        type="email"
                        class="form-control"
                        id="email"
                        placeholder="example@bla.com"
                        autocomplete="email"
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
                        placeholder="•••••••••••"
                        autocomplete="new-password"
                        bind:value={$form.password}
                    />
                    {#if $form.errors.password}
                        <div class="text-danger">{$form.errors.password}</div>
                    {/if}
                </div>
                <div class="d-flex flex-row gap-1">
                    <p>Already have an account?</p>
                    <Link href="/login">Log In</Link>
                </div>
                <button class="btn btn-primary" type="submit">Submit</button>
            </form>
        </div>
    </body>
</html>
