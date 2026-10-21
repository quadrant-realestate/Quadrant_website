import { activeCommunities } from "@/lib/admin/developments";
import { activeProperties } from "@/lib/admin/investments";
import { PageHeader } from "../../_projects/PageHeader";
import { InvestmentForm } from "../_components/InvestmentForm";

export default async function CreateInvestment() {
  const [communities, properties] = await Promise.all([activeCommunities(), activeProperties()]);

  return (
    <div className="body-wrapper-inner">
      <div className="container-fluid">
        <PageHeader title="Add Investment" crumbs={["Investments", "Add"]} />
        <InvestmentForm communities={communities} properties={properties} />
      </div>
    </div>
  );
}
