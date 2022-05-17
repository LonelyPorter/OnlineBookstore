package com.example.bookstore;

import android.content.Context;
import android.graphics.Color;
import android.os.Bundle;
import android.util.Log;
import android.view.Gravity;
import android.view.View;
import android.widget.Button;
import android.widget.ImageButton;
import android.widget.ImageView;
import android.widget.TableLayout;
import android.widget.TableRow;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.Nullable;
import androidx.appcompat.app.AppCompatActivity;

import com.android.volley.AuthFailureError;
import com.android.volley.Cache;
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

public class CartActivity extends AppCompatActivity {
    String id;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_cart);
        RequestQueue requestQueue = Volley.newRequestQueue(this);

        // get information from previous page
        id = getIntent().getStringExtra("id");

        // send request to web to get information back
        StringRequest stringRequest = new StringRequest(Request.Method.POST, getString(R.string.url) + "cart.php",
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        // debug
                        Log.d("Cart", "response: " + response);

                        // get content
                        Context context = CartActivity.this;
                        TableLayout cartTable = (TableLayout) findViewById(R.id.cartTable);

                        JSONArray carts = null;
                        try {
                            carts = new JSONArray(response);

                            //parse the carts data
                            for (int i = 0; i < carts.length(); i++) {
                                JSONObject cart = carts.getJSONObject(i);
                                // create row
                                TableRow row = new TableRow(context);
                                TableRow.LayoutParams layoutParams = new TableRow.LayoutParams(TableRow.LayoutParams.WRAP_CONTENT);
                                row.setLayoutParams(layoutParams);

                                // add textView into the row
                                TextView title = new TextView(context);
                                TableRow.LayoutParams l1 = new TableRow.LayoutParams(TableRow.LayoutParams.WRAP_CONTENT, 100, 2);
                                TextView quantity = new TextView(context);
                                TableRow.LayoutParams l2 = new TableRow.LayoutParams(TableRow.LayoutParams.WRAP_CONTENT, TableRow.LayoutParams.MATCH_PARENT, 1);
                                TextView price = new TextView(context);
                                TableRow.LayoutParams l3 = new TableRow.LayoutParams(TableRow.LayoutParams.WRAP_CONTENT, TableRow.LayoutParams.MATCH_PARENT, 1);

                                // title
                                title.setLayoutParams(l1);
                                title.setText(cart.getString("title"));
                                title.setGravity(Gravity.CENTER);
                                title.setBackground(getDrawable(R.drawable.border));

                                // quantity
                                quantity.setLayoutParams(l2);
                                quantity.setText(cart.getString("quantity"));
                                quantity.setGravity(Gravity.CENTER);
                                quantity.setBackground(getDrawable(R.drawable.border));

                                // total price
                                price.setLayoutParams(l3);
                                price.setText(cart.getString("total price"));
                                price.setGravity(Gravity.CENTER);
                                price.setBackground(getDrawable(R.drawable.border));

                                // ISBN (info)
                                String ISBN = cart.getString("ISBN");

                                // add them to the row
                                row.addView(title);
                                row.addView(quantity);
                                row.addView(price);

                                // delete button
                                TableRow.LayoutParams l4 = new TableRow.LayoutParams(TableRow.LayoutParams.WRAP_CONTENT, TableRow.LayoutParams.MATCH_PARENT);
                                ImageButton delete = new ImageButton(context);
                                delete.setLayoutParams(l4);
                                delete.setImageResource(android.R.drawable.ic_delete);
                                delete.setScaleType(ImageView.ScaleType.CENTER_CROP);
                                delete.setBackgroundColor(Color.TRANSPARENT);
                                row.addView(delete);

                                // on clicked
                                delete.setOnClickListener(new View.OnClickListener() {
                                    @Override
                                    public void onClick(View v) {
                                        StringRequest stringRequest1 = new StringRequest(Request.Method.POST, getString(R.string.url) + "cart.php",
                                                new Response.Listener<String>() {
                                                    @Override
                                                    public void onResponse(String response) {
                                                        // reload
                                                        finish();
                                                        startActivity(getIntent());
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
                                                map.put("delete", ISBN);
                                                map.put("id", id);
                                                return map;
                                            }
                                        };

                                        requestQueue.add(stringRequest1);
                                    }
                                });

                                // add row to the table
                                cartTable.addView(row, i + 1);
                            }

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
                Map<String, String> map = new HashMap<>();
                map.put("id", id);
                map.put("cart", "true");
                return map;
            }
        };

        requestQueue.add(stringRequest);
    }

    public void purchaseOnClicked(View view) {
        RequestQueue requestQueue = Volley.newRequestQueue(this);

        StringRequest stringRequest = new StringRequest(Request.Method.POST, getString(R.string.url) + "cart.php",
                new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        Log.d("Cart", "Purchase: "+response);
                        
                        if(response.equals("error")) {
                            Toast.makeText(CartActivity.this, "Purchase Failed!", Toast.LENGTH_SHORT).show();
                        } else {
                            Toast.makeText(CartActivity.this, "Succeed!", Toast.LENGTH_SHORT).show();
                            finish();
                            startActivity(getIntent());
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
                Map<String, String> map = new HashMap<>();
                map.put("purchase", "true");
                map.put("id", id);
                return map;
            }
        };

        requestQueue.add(stringRequest);
    }
}