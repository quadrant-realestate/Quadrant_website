import { redirect } from "next/navigation";
import { AdminFlash } from "@/components/admin/AdminFlash";
import { activeCommunities, developmentGallery, getDevelopment } from "@/lib/admin/developments";
import { toId } from "@/lib/form";
import { PageHeader } from "../../../_projects/PageHeader";
import { DevelopmentForm } from "../../_components/DevelopmentForm";

export default async function EditDevelopment({ params }: { params: Promise<{ id: string }> }) {
  const id = toId((await params).id);
  const development = id ? await getDevelopment(id) : null;
  if (!development) redirect("/admin/projects-record-missing?m=development");

  const [communities, gallery] = await Promise.all([activeCommunities(), developmentGallery(development.id)]);

  return (
    <div className="body-wrapper-inner">
      <div className="container-fluid">
        <PageHeader title="Edit Development" crumbs={["Developments", "Edit"]} />
        <DevelopmentForm communities={communities} development={development} gallery={gallery} flash={<AdminFlash />} />
      </div>
    </div>
  );
}
