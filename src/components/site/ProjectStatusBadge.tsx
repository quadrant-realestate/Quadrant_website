import type { CurrentProject } from "@/lib/queries/current-projects";

const LABELS: Record<CurrentProject["status"], string> = {
  pending: "Pending",
  processing: "Processing",
};

/** Icon and label on a current project's image, so visitors can see the work is still under way. */
export function ProjectStatusBadge({ status }: { status: CurrentProject["status"] }) {
  return (
    <span className={`qp-progress-badge qp-progress-badge--${status}`}>
      <svg fill="none" viewBox="0 0 24 24" strokeWidth="2" aria-hidden="true">
        {status === "pending" ? (
          <path strokeLinecap="round" strokeLinejoin="round" d="M12 6v6l4 2m5-2a9 9 0 11-18 0 9 9 0 0118 0z" />
        ) : (
          <path
            strokeLinecap="round"
            strokeLinejoin="round"
            d="M16 9h5V4m-.5 5A9 9 0 003.6 8.9M8 15H3v5m.5-5a9 9 0 0016.9.1"
          />
        )}
      </svg>
      {LABELS[status]}
    </span>
  );
}
