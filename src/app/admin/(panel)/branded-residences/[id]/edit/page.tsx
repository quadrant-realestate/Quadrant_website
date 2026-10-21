import { redirect } from "next/navigation";
import { AdminFlash } from "@/components/admin/AdminFlash";
import { getBrandedResidence } from "@/lib/admin/branded-residences";
import { activeCommunities } from "@/lib/admin/developments";
import { activeProperties } from "@/lib/admin/investments";
import { toId } from "@/lib/form";
import { PageHeader } from "../../../_projects/PageHeader";
import { BrandedResidenceForm } from "../../_components/BrandedResidenceForm";

export default async function EditBrandedResidence({ params }: { params: Promise<{ id: string }> }) {
  const id = toId((await params).id);
  const residence = id ? await getBrandedResidence(id) : null;
  if (!residence) redirect("/admin/projects-record-missing?m=branded-residence");

  const [communities, properties] = await Promise.all([activeCommunities(), activeProperties()]);

  return (
    <div className="body-wrapper-inner">
      <div className="container-fluid">
        <PageHeader title="Edit Branded Residence" crumbs={["Branded Residences", "Edit"]} />
        <BrandedResidenceForm communities={communities} properties={properties} residence={residence} flash={<AdminFlash />} />
      </div>
    </div>
  );
}
