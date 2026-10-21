import type { Metadata } from "next";
import { SignInForm } from "./SignInForm";

export const metadata: Metadata = { title: "Quadrant | Dashboard" };

export default function SignInPage() {
  return (
    <>
      <div id="main-wrapper">
        <div className="position-relative overflow-hidden auth-bg min-vh-100 w-100 d-flex align-items-center justify-content-center">
          <div className="d-flex align-items-center justify-content-center w-100">
            <div className="row justify-content-center w-100 my-5 my-xl-0">
              <div className="col-md-9 d-flex flex-column justify-content-center">
                <div className="card mb-0 bg-body auth-login m-auto w-100">
                  <div className="row gx-0">
                    <div className="col-xl-12">
                      <div className="row justify-content-center py-4">
                        <div className="col-lg-11">
                          <div className="card-body">
                            <a href="/admin/sign-in" className="text-nowrap logo-img d-block mb-4 w-100 text-center">
                              <img src="/public/logo-quadrant.png" className="dark-logo" alt="Logo-Dark" style={{ width: "25%" }} />
                            </a>
                            <h2 className="lh-base mb-4">Let&apos;s get you signed in</h2>
                            <SignInForm />
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </>
  );
}
