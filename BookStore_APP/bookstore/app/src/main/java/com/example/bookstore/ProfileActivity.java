package com.example.bookstore;

import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.TextView;

import androidx.annotation.Nullable;
import androidx.appcompat.app.AppCompatActivity;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.HashMap;
import java.util.Map;

public class ProfileActivity<onPrimeClicked> extends AppCompatActivity {
    String id;
    TextView primeStatus;
    TextView profileName;
    TextView profileEmail;
    TextView profilePhone;
    TextView profileAddr;
    String status; // for update

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_profile);
        RequestQueue requestQueue = Volley.newRequestQueue(this);

        // get information from other activity
        id = getIntent().getStringExtra("id");

        status = "1"; // default is on prime button

        // set profile textview
        primeStatus = findViewById(R.id.textView_primeStatus);
        profileName = findViewById(R.id.textView_profileName);
        profileEmail = findViewById(R.id.textView_profileEmail);
        profilePhone = findViewById(R.id.textView_profilePhone);
        profileAddr = findViewById(R.id.textView_profileAddr);

        // update button
        Button updateBtn = findViewById(R.id.update_btn);
        updateBtn.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                StringRequest stringRequest = new StringRequest(Request.Method.POST, getString(R.string.url) + "prime.php",
                        new Response.Listener<String>() {
                            @Override
                            public void onResponse(String response) {
                                /* set profile info */
                                String primeInfo = "";
                                // set prime info
                                if (status.equals("0")) {
                                    primeInfo = "Membership:  Non Prime";
                                } else if (status.equals("1")) {
                                    primeInfo = "Membership:  Prime";
                                } else if (status.equals("-1")) {
                                    primeInfo = "Membership:  N/A";
                                }

                                primeStatus.setText(primeInfo);
                            }
                        }, new Response.ErrorListener() {
                    @Override
                    public void onErrorResponse(VolleyError error) {

                    }
                }) {
                    @Nullable
                    @Override
                    protected Map<String, String> getParams() throws AuthFailureError {
                        Map<String, String> map = new HashMap<>();
                        map.put("id", id);
                        map.put("status", status);
                        return map;
                    }
                };
                // send request
                requestQueue.add(stringRequest);
            }
        });

        // Connect to php to retrieve data and create table
        StringRequest stringRequest = new StringRequest(Request.Method.POST,
                getString(R.string.url) + "profile.php",
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        try {
                            Log.d("Profile", "response: " + response);

                            JSONArray jsonArray = new JSONArray(response);
                            // user information
                            JSONObject jsonObject = jsonArray.getJSONObject(0);
                            int prime = jsonObject.getInt("prime");
                            String name = jsonObject.getString("name");
                            String email = jsonObject.getString("email");
                            String phone = jsonObject.getString("phone");
                            String address = jsonObject.getString("address");

                            /* set profile info */
                            String primeInfo = "";
                            // set prime info
                            if (prime == 0) {
                                primeInfo = "Membership:  Non Prime";
                            } else if (prime == 1) {
                                primeInfo = "Membership:  Prime";
                            } else if (prime == -1) {
                                primeInfo = "Membership:  N/A";
                                Button button = findViewById(R.id.update_btn);
                                button.setEnabled(false);
                            }
                            String nameInfo = "Name:  " + name;
                            String emailInfo = "Email:  " + email;
                            String phoneInfo = "Phone:  " + phone;
                            String addrInfo = "Address:  " + address;

                            // display profile info
                            primeStatus.setText(primeInfo);
                            profileName.setText(nameInfo);
                            profileEmail.setText(emailInfo);
                            profilePhone.setText(phoneInfo);
                            profileAddr.setText(addrInfo);

                        } catch (JSONException e) {
                            e.printStackTrace();
                        }
                    }
                }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {

            }
        }) {
            @Nullable
            @Override
            protected Map<String, String> getParams() throws AuthFailureError {
                Map<String, String> map = new HashMap<String, String>();
                map.put("id", id);
                return map;
            }
        };

        requestQueue.add(stringRequest);
    }

    // prime update
    public void onPrimeClicked(View view) {
        // get data
        switch (view.getId()) {
            case R.id.prime_btn:
                status = "1";
                break;
            case R.id.nonPrime_btn:
                status = "0";
                break;
        }
    }
}