"use client";

/**
 * A submit button that asks for confirmation first — replaces the old
 * `<a href=".../delete" onclick="return confirm(...)">` links. Put it inside a <form action={serverAction}>.
 */
export function ConfirmButton({
  message = "Are you sure you want to delete this?",
  className,
  title,
  children,
}: {
  message?: string;
  className?: string;
  title?: string;
  children: React.ReactNode;
}) {
  return (
    <button
      type="submit"
      className={className}
      title={title}
      onClick={(e) => {
        if (!window.confirm(message)) e.preventDefault();
      }}
    >
      {children}
    </button>
  );
}
