import { activeCommunities } from "@/lib/admin/developments";
import { activeProperties } from "@/lib/admin/investments";
import { PageHeader } from "../../_projects/PageHeader";
import { BrandedResidenceForm } from "../_components/BrandedResidenceForm";

export default async function CreateBrandedResidence() {
  const [communities, properties] = await Promise.all([activeCommunities(), activeProperties()]);

  return (
    <div className="body-wrapper-inner">
      <div className="container-fluid">
        <PageHeader title="Add Branded Residence" crumbs={["Branded Residences", "Add"]} />
        <BrandedResidenceForm communities={communities} properties={properties} />
      </div>
    </div>
  );
}
