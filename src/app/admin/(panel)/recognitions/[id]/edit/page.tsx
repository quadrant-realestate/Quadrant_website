import { redirect } from "next/navigation";
import { AdminFlash } from "@/components/admin/AdminFlash";
import { getRecognition } from "@/lib/admin/recognitions";
import { toId } from "@/lib/form";
import { PageHeader } from "../../../_projects/PageHeader";
import { RecognitionForm } from "../../_components/RecognitionForm";

export default async function EditRecognition({ params }: { params: Promise<{ id: string }> }) {
  const id = toId((await params).id);
  const recognition = id ? await getRecognition(id) : null;
  if (!recognition) redirect("/admin/projects-record-missing?m=recognition");

  return (
    <div className="body-wrapper-inner">
      <div className="container-fluid">
        <PageHeader title="Edit Recognition" crumbs={["Recognitions", "Edit"]} />
        <RecognitionForm recognition={recognition} flash={<AdminFlash />} />
      </div>
    </div>
  );
}
