<script>
    import { onMount } from "svelte";

    let calendarElement;

    onMount(() => {
        const calendar = bulmaCalendar.attach(calendarElement, {
            // Options here
            type: "date",
            dateFormat: "YYYY-MM-DD",
        });
    });
</script>

<input bind:this={calendarElement} type="date" />
