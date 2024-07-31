<script>
    import { Link } from "@inertiajs/svelte";
</script>

<html lang="en" data-bs-theme="dark">
    <body>
        <div class="d-flex flex-column justify-content-center w-100">
            <nav
                class="w-100 h-25 d-flex flex-row align-items-center justify-content-center"
            >
                <img src="/Logo.svg" alt="Logo" class="" style="width: 10%" />
                <div>
                    <h3>Simple Todo-List</h3>
                    <p>Made by Ezizmuhammet</p>
                </div>
            </nav>
            <header class="d-flex justify-content-center">
                <h1 class="text-white text-light-emphasis">
                    Managing your tasks is now easier.
                </h1>
            </header>
            <main class="d-flex flex-row justify-content-center gap-3">
                <Link href="/login">
                    <button class="btn btn-primary btn-lg">Log In</button>
                </Link>
                <Link href="/signup"
                    ><button class="btn btn-secondary btn-lg">Sign Up</button>
                </Link>
            </main>
        </div>
    </body>
</html>
