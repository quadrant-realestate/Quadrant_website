import { PageHeader } from "../../_projects/PageHeader";
import { RecognitionForm } from "../_components/RecognitionForm";

export default function CreateRecognition() {
  return (
    <div className="body-wrapper-inner">
      <div className="container-fluid">
        <PageHeader title="Add Recognition" crumbs={["Recognitions", "Add"]} />
        <RecognitionForm />
      </div>
    </div>
  );
}
